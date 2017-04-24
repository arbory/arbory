<?php

namespace CubeSystems\Leaf\Support\Facades;

use Illuminate\Support\Facades\Facade;

class AdminModule extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'leaf_module_builder';
    }
}