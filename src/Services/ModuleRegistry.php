<?php

namespace CubeSystems\Leaf\Services;

use CubeSystems\Leaf\Admin\Admin;

/**
 * Class ModuleRegistryService
 * @package CubeSystems\Leaf\Services
 */
class ModuleRegistry
{
    protected $admin;

    /**
     * @var Module[]
     */
    protected $modulesByController = [];

    /**
     * ModuleRegistry constructor.
     * @param Admin $admin
     */
    public function __construct( Admin $admin )
    {
        $this->admin = $admin;
    }

    /**
     * @param string $controllerClass
     * @param \Closure|null $routes
     * @return Module
     */
    public function register( string $controllerClass, \Closure $routes = null )
    {
        if( array_key_exists( $controllerClass, $this->modulesByController ) )
        {
            throw new \LogicException( 'Module with controller class "' . $controllerClass . '" already registered' );
        }

        $config = new ModuleConfiguration( $controllerClass );
        $module = new Module( $this->admin, $config );

        $this->admin->routes()->register( $module, $routes );

        $this->modulesByController[$controllerClass] = $module;

        return $module;
    }

    /**
     * @param string $controllerClass
     * @return Module
     */
    public function findModuleByControllerClass( $controllerClass )
    {
        return array_get( $this->modulesByController, $controllerClass );
    }

    /**
     * @param $instance
     * @return Module
     */
    public function findModuleByController( $instance )
    {
        return $this->findModuleByControllerClass( get_class( $instance ) );
    }

    /**
     * @return Module[]
     */
    public function getModulesByControllerClass(): array
    {
        return $this->modulesByController;
    }
}
