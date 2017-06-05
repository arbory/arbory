<?php

namespace CubeSystems\Leaf\Services;

use CubeSystems\Leaf\Admin\Admin;
use Illuminate\Support\Collection;

/**
 * Class ModuleRegistryService
 * @package CubeSystems\Leaf\Services
 */
class ModuleRegistry extends Collection
{
    /**
     * @var Admin
     */
    protected $admin;

    /**
     * ModuleRegistry constructor.
     * @param Admin $admin
     */
    public function __construct( Admin $admin )
    {
        $this->admin = $admin;

        parent::__construct();
    }

    /**
     * @param string $controllerClass
     * @param \Closure|null $routes
     * @return Module
     */
    public function register( string $controllerClass, \Closure $routes = null )
    {
        if( $this->has( $controllerClass ) )
        {
            throw new \LogicException( 'Module with controller class "' . $controllerClass . '" already registered' );
        }

        $config = new ModuleConfiguration( $controllerClass );
        $module = new Module( $this->admin, $config );

        $this->admin->routes()->register( $module, $routes );

        $this[$controllerClass] = $module;

        return $module;
    }

    /**
     * @param string $controllerClass
     * @return Module
     */
    public function findModuleByControllerClass( $controllerClass )
    {
        return $this->get( $controllerClass );
    }

    /**
     * @param $instance
     * @return Module
     */
    public function findModuleByController( $instance )
    {
        return $this->findModuleByControllerClass( get_class( $instance ) );
    }
}
