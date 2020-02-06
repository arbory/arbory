<?php

namespace Arbory\Base\Admin\Grid;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

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
     * @return self
     */
    public function execute(Collection $columns);

    /**
     * @return mixed
     */
    public function loadItems();

    /**
     * @param $relationName
     */
    public function withRelation(string $relationName);

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function getQuery();
}
