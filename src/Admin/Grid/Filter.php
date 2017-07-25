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
     * @return void
     */
    protected function order()
    {
        $orderBy = $this->request->get( '_order_by' );

        if(
            !$orderBy
            ||
            strpos( $orderBy, '.' ) !== false
        )
        {
            return;
        }

        $this->query->orderBy( $this->request->get( '_order_by' ), $this->request->get( '_order', 'asc' ) );
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

        $this->order();

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
}
