<?php

namespace Arbory\Base\Repositories;

/**
 * Class ResourcesRepository
 * @package Arbory\Base\Repositories
 */
class ResourcesRepository extends AbstractModelsRepository
{
    /**
     * ResourcesRepository constructor.
     * @param $class
     */
    public function __construct($class)
    {
        $this->modelClass = $class;

        parent::__construct();
    }
}
