<?php

namespace Arbory\Base\Admin\Module;

use Closure;
use Arbory\Base\Admin\Module;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;

/**
 * Class ModuleRoutesRegistry
 * @package Arbory\Base\Admin\Module
 */
class ModuleRoutesRegistry
{
    /**
     * @var ResourceRoutes[]
     */
    protected $routes = [];

    /**
     * @param \Arbory\Base\Admin\Module $module
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

        $router->any( $slug . '/api/{api}', [
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
        return Arr::get( $this->routes, $module->name() );
    }
}
