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
     * @var Builder
     */
    protected $query;

    /**
     * Filter constructor.
     *
     * @param  Model  $model
     */
    public function __construct(protected Model $model)
    {
        $this->query = $model->newQuery();
    }

    /**
     * @return $this
     */
    public function execute(Collection $columns): self
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
     * @return void
     */
    public function withRelation(string $relationName)
    {
        $this->query->with($relationName);
    }

    public function getQuery(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        return $this->query;
    }
}
