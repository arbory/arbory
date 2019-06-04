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
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->query = $model->newQuery();
    }

    /**
     * @param Collection $columns
     * @return mixed
     */
    public function execute(Collection $columns)
    {
        $items = $this->query->get();
        $hierarchy = $items->toHierarchy();

        return new LengthAwarePaginator($hierarchy, $items->count(), $hierarchy->count() ?: 1);
    }

    /**
     * @param $relationName
     */
    public function withRelation($relationName)
    {
        $this->query->with($relationName);
    }
}
