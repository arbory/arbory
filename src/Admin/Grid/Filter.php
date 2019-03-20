<?php

namespace Arbory\Base\Admin\Grid;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class Filter
 * @package Arbory\Base\Admin\Grid
 */
class Filter implements FilterInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var QueryBuilder
     */
    protected $query;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var bool
     */
    protected $paginated = true;

    /**
     * @var int
     */
    protected $perPage;

    /**
     * Filter constructor.
     * @param Model $model
     */
    public function __construct( Model $model )
    {
        $this->model = $model;
        $this->query = $model->newQuery();
        $this->request = request();
    }

    /**
     * @param Collection|Column[] $columns
     * @return void
     */
    protected function order(Collection $columns)
    {
        $orderBy = $this->request->get('_order_by');
        $orderDirection = $this->request->get('_order', 'asc');

        if (!$orderBy) {
            return;
        }

        $column = $columns->filter(function (Column $column) {
            return $column->isSortable();
        })->filter(function (Column $column) use ($orderBy) {
            return $column->getName() === $orderBy;
        })->first();

        if (!$column) {
            return;
        }

        $this->query->orderBy($column->getName(), $orderDirection);
    }

    protected function filter(Collection $columns)
    {
        $filterParameters = self::removeNonFilterParameters($this->request->all());

        foreach ($filterParameters as $getKey => $getValue ) {

            if (!$getValue) {
                continue;
            }

            $column = $columns->filter(function (Column $column) use ($getKey) {
                return $column->getName() === $getKey;
            })->first();

            if (!$column) {
                continue;
            }

            if (!$column->getHasFilter()) {
                continue;
            }

            $columnName = self::getColumnName( $getKey, $column );

            if ( !is_array($getValue) ) {
                $this->query->where($columnName, $column->getFilterType()->getAction(), $getValue);
            } elseif ( is_array( $getValue ) && is_array($column->getFilterType()->getAction()) ) {
                foreach (array_combine( $column->getFilterType()->getAction(), $getValue ) as $action => $value ) {
                    $this->query->where($columnName, $action, $value);
                }
            } elseif ( is_array( $getValue ) ){
                $this->query->where(function ($query) use ($columnName, $column, $getValue)
                {
                    foreach ($getValue as $value)
                    {
                        $query->orWhere($columnName, $column->getFilterType()->getAction(), $value);
                    }
                });
            } else {

                dd($getValue);

            }
        }

        dump('query',$this->query->toSql());
    }

    /**
     * @param $phrase
     * @param Collection|Column[] $columns
     */
    protected function search( $phrase, $columns )
    {
        $keywords = explode( ' ', $phrase );

        foreach( $keywords as $string )
        {
            $this->query->where( function ( QueryBuilder $query ) use ( $string, $columns )
            {
                foreach( $columns as $column )
                {
                    if( !$column->isSearchable() )
                    {
                        continue;
                    }

                    $column->searchConditions( $query, $string );
                }
            } );
        }
    }

    /**
     * @return Collection|LengthAwarePaginator
     */
    protected function loadItems()
    {
        $result = $this->query;

        if (! $this->isPaginated()) {
            return $result->get();
        }

        /** @var LengthAwarePaginator $result */
        $result = $this->query->paginate( $this->getPerPage() );

        if( $this->request->has( 'search' ) )
        {
            $result->appends([
                'search' => $this->request->get( 'search' ),
            ]);
        }

        if( $this->request->has( '_order_by' ) && $this->request->has( '_order' ) )
        {
            $result->appends([
                '_order_by' => $this->request->get( '_order_by' ),
                '_order' => $this->request->get( '_order' ),
            ]);
        }

        return $result;
    }

    /**
     * @param Collection|Column[] $columns
     * @return Collection|LengthAwarePaginator
     */
    public function execute( Collection $columns )
    {
        if( $this->request->has( 'search' ) )
        {
            $this->search( $this->request->get( 'search' ), $columns );
        }

        $this->filter( $columns );

        $this->order( $columns );

        return $this->loadItems();
    }

    /**
     * @param $relationName
     */
    public function withRelation( $relationName )
    {
        $this->query->with( $relationName );
    }

    /**
     * @return QueryBuilder
     */
    public function getQuery(): QueryBuilder
    {
        return $this->query;
    }

    /**
     * @return bool
     */
    public function isPaginated(): bool
    {
        return $this->paginated;
    }

    /**
     * @param bool $paginated
     */
    public function setPaginated( bool $paginated )
    {
        $this->paginated = $paginated;
    }

    /**
     * @return int|null
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage( int $perPage )
    {
        $this->perPage = $perPage;
    }

    /**
     * @return Model
     */
    public function getColumnName( $getColumn, $column ) {
        if ($column->getFilterType()->column) {
            return $column->getFilterType()->column;
        } else {
            return $getColumn;
        }
    }

    private function removeNonFilterParameters( $parameters ) {
        unset($parameters['_order_by']);
        unset($parameters['_order']);

        return self::recursiveArrayFilter($parameters);
    }

    private function recursiveArrayFilter( array $array ) {

        foreach ( $array as $key => &$value ) {

            if ( is_array( $value ) ) {
                $value = self::recursiveArrayFilter( $value );
            }

        }

        $array = array_filter( $array );

        return $array;
    }
}
