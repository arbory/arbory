<?php

namespace Arbory\Base\Admin\Grid;

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
     */
    public function __construct(Model $model);

    /**
     * @param Collection $columns
     * @return Paginator
     */
    public function execute(Collection $columns);

    /**
     * @param $relationName
     */
    public function withRelation($relationName);
}
