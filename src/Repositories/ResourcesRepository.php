<?php

namespace CubeSystems\Leaf\Repositories;

/**
 * Class ResourcesRepository
 * @package CubeSystems\Leaf\Repositories
 */
class ResourcesRepository extends AbstractModelsRepository
{
    /**
     * ResourcesRepository constructor.
     * @param $class
     */
    public function __construct( $class )
    {
        $this->modelClass = $class;

        parent::__construct();
    }
}
