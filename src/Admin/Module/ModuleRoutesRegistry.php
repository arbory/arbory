<?php

namespace CubeSystems\Leaf\Admin\Module;

use Closure;
use CubeSystems\Leaf\Admin\Module;
use Illuminate\Routing\Router;

/**
 * Class ModuleRoutesRegistry
 * @package CubeSystems\Leaf\Admin\Module
 */
class ModuleRoutesRegistry
{
    /**
     * @var ResourceRoutes[]
     */
    protected $routes = [];

    /**
     * @param \CubeSystems\Leaf\Admin\Module $module
     * @param Closure|null $callback
     * @return ResourceRoutes
     */
    public function register( Module $module, Closure $callback = null )
    {
        $class = $module->getControllerClass();
        $slug = $module->name();

        /**
         * @var $router Router
         */
        $router = app( 'router' );

        if( $callback !== null )
        {
            $attributes = [
                'as' => $slug . '.',
                'prefix' => $slug,
            ];

            $router->group( $attributes, $callback );
        }

        $router->resource( $slug, '\\' . $class );

        $router->get( $slug . '/dialog/{dialog}', [
            'as' => $slug . '.dialog',
            'uses' => '\\' . $class . '@dialog'
        ] );

        $router->get( $slug . '/api/{api}', [
            'as' => $slug . '.api',
            'uses' => '\\' . $class . '@api'
        ] );

        $router->get( $slug . '/export/{as}', [
            'as' => $slug . '.export',
            'uses' => '\\' . $class . '@export'
        ] );

        $this->routes[$module->name()] = new ResourceRoutes( $module );

        return $this->routes[$module->name()];
    }

    /**
     * @param Module $module
     * @return ResourceRoutes
     */
    public function findByModule( Module $module )
    {
        return array_get( $this->routes, $module->name() );
    }
}
