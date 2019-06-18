<?php

namespace Arbory\Base\Admin\Grid;

use Arbory\Base\Admin\Filter\FilterBuilder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * Interface FilterInterface.
 */
interface FilterInterface
{
    /**
     * FilterInterface constructor.
     * @param Model $model
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(Model $model, FilterBuilder $filterBuilder);

    /**
     * @param Collection $columns
     * @return Paginator
     */
    public function execute(Collection $columns);

    /**
     * @param $relationName
     */
    public function withRelation($relationName);

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function getQuery();
}
