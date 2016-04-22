<?php

namespace CubeSystems\Leaf\Repositories;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class ResourcesRepository
 * @package CubeSystems\Leaf\Repositories
 */
class ResourcesRepository extends GenericRepository
{
    /**
     * ResourcesRepository constructor.
     * @param $class
     */
    public function __construct( $class )
    {
        $this->class = $class;

        parent::__construct();
    }
}
