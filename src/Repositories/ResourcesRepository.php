<?php

namespace CubeSystems\Leaf\Repositories;

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
