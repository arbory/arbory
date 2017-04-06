<?php

namespace CubeSystems\Leaf\Generator;

use CubeSystems\Leaf\Generator\Generateable\Model;

class ModelGenerator
{
    /**
     * @var Model
     */
    protected $module;

    public function __construct( Model $module)
    {
        $this->module = $module;
    }
}