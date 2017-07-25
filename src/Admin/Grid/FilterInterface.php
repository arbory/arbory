<?php

namespace Arbory\Base\Admin\Grid;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Interface FilterInterface
 * @package Arbory\Base\Admin\Grid
 */
interface FilterInterface
{
    /**
     * FilterInterface constructor.
     * @param Model $model
     */
    public function __construct( Model $model );

    /**
     * @param Collection $columns
     * @return Paginator
     */
    public function execute( Collection $columns );

    /**
     * @param $relationName
     */
    public function withRelation( $relationName );
}
