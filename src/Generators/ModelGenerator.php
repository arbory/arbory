<?php

namespace CubeSystems\Leaf\Generators;

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