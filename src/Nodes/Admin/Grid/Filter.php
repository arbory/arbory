<?php

namespace CubeSystems\Leaf\Nodes\Admin\Grid;

use CubeSystems\Leaf\Admin\Grid\FilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class Filter implements FilterInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Builder
     */
    protected $query;

    /**
     * Filter constructor.
     * @param Model $model
     */
    public function __construct( Model $model )
    {
        $this->model = $model;
        $this->query = $model->newQuery();
    }

    /**
     * @param Collection $columns
     * @return mixed
     */
    public function execute( Collection $columns )
    {
        $items = $this->query->get();
        $hierarchy = $items->toHierarchy();

        return new LengthAwarePaginator( $hierarchy, $items->count(), $hierarchy->count() ?: 1 );
    }

    /**
     * @param $relationName
     */
    public function withRelation( $relationName )
    {
        $this->query->with( $relationName );
    }
}
