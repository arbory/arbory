<?php

namespace CubeSystems\Leaf\Services;

use CubeSystems\Leaf\Admin\Module\Route;

class ModuleBuilder
{
    /**
     * @var ModuleRegistry
     */
    protected $moduleRegistry;

    /**
     * @var Module
     */
    protected $module;

    /**
     * @param ModuleRegistry $moduleRegistry
     */
    public function __construct( ModuleRegistry $moduleRegistry )
    {
        $this->moduleRegistry = $moduleRegistry;
    }

    /**
     * @param string $controllerClass
     * @return self
     */
    public function register( string $controllerClass )
    {
        $this->module = ModuleFactory::build( $controllerClass );

        $this->moduleRegistry->register( $this->module );

        Route::register( $this->module->getControllerClass() );

        return $this;
    }

    /**
     * @param callable $routeCallback
     * @return $this
     */
    public function routes( callable $routeCallback )
    {
        Route::register( $this->module->getControllerClass(), $routeCallback );

        return $this;
    }

    /**
     * @return Module
     */
    public function getModule(): Module
    {
        return $this->module;
    }
}