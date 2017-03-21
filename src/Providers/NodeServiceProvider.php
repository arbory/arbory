<?php

namespace CubeSystems\Leaf\Providers;

use CubeSystems\Leaf\Nodes\ContentTypeRegister;
use CubeSystems\Leaf\Nodes\ContentTypeRoutesRegister;
use CubeSystems\Leaf\Nodes\Node;
use CubeSystems\Leaf\Nodes\Routing\Router;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router as LaravelRouter;
use Illuminate\Support\ServiceProvider;

/**
 * Class NodeServiceProvider
 * @package CubeSystems\Leaf\Providers
 */
class NodeServiceProvider extends ServiceProvider
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Router
     */
    protected $routes;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->routes = new ContentTypeRoutesRegister( $this->app );

        $this->app->singleton( ContentTypeRegister::class, function ()
        {
            return new ContentTypeRegister();
        } );

        $this->app->singleton( 'leaf_router', function ()
        {
            return $this->routes;
        } );
    }

    /**
     * @return void
     */
    public function boot()
    {
        if( !app()->runningInConsole() )
        {
            $this->app->booted( function ()
            {
                $this->app->singleton( Node::class, function ()
                {
                    return $this->routes->getCurrentNode();
                } );
            } );
        }

        if( !$this->app->routesAreCached() )
        {
            $this->routes->registerNodes();
        }

        $this->detectCurrentLocaleFromRoute( $this->app['router'] );
    }

    /**
     * @param LaravelRouter $router
     */
    protected function detectCurrentLocaleFromRoute( LaravelRouter $router )
    {
        $router->matched( function ( RouteMatched $event )
        {
            $locales = config( 'translatable.locales' );
            $firstSegment = $event->request->segment( 1 );

            if( in_array( $firstSegment, $locales, true ) )
            {
                $this->app->setLocale( $firstSegment );
                $this->app['request']->setLocale( $firstSegment );
            }
        } );
    }
}
