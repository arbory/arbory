<?php

namespace CubeSystems\Leaf\Admin\Grid;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class Filter
 * @package CubeSystems\Leaf\Admin\Grid
 */
class Filter implements FilterInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Request
     */
    protected $request;

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
     *
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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function loadPage()
    {
        $page = $this->query->paginate();

        if( $this->request->has( '_order_by' ) && $this->request->has( '_order' ) )
        {
            $page->addQuery( '_order_by', $this->request->get( '_order_by' ) );
            $page->addQuery( '_order', $this->request->get( '_order' ) );
        }

        if( $this->request->has( 'search' ) )
        {
            $page->addQuery( 'search', $this->request->get( 'search' ) );
        }

        return $page;
    }

    /**
     * @param Collection|Column[] $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function execute( Collection $columns )
    {
        if( $this->request->has( 'search' ) )
        {
            $this->search( $this->request->get( 'search' ), $columns );
        }

        $this->order();

        return $this->loadPage();
    }

    /**
     * @param $relationName
     */
    public function withRelation( $relationName )
    {
        $this->query->with( $relationName );
    }
}
