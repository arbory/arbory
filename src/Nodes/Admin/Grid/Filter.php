<?php

namespace Arbory\Base\Nodes\Admin\Grid;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Arbory\Base\Admin\Grid\FilterInterface;
use Illuminate\Pagination\LengthAwarePaginator;

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
     * @param \Closure $closure
     * @return $this
     */
    public function apply(\Closure $closure) {
        $this->query = $closure($this->query) ?: $this->query;

        return $this;
    }

    /**
     * @param Collection $columns
     * @return $this
     */
    public function execute( Collection $columns ): self
    {
        return $this;
    }

    /**
     * @return LengthAwarePaginator|mixed
     */
    public function loadItems()
    {
        $items = $this->query->get();
        $hierarchy = $items->toHierarchy();

        return new LengthAwarePaginator($hierarchy, $items->count(), $hierarchy->count() ?: 1);
    }

    /**
     * @param string $relationName
     */
    public function withRelation( string $relationName )
    {
        $this->query->with( $relationName );
    }

    /**
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function getQuery()
    {
        return $this->query;
    }
}

