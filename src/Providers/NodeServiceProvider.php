<?php

namespace CubeSystems\Leaf\Providers;

use CubeSystems\Leaf\Nodes\ContentTypeRegister;
use CubeSystems\Leaf\Nodes\ContentTypeRoutesRegister;
use CubeSystems\Leaf\Nodes\Node;
use CubeSystems\Leaf\Services\Content\PageBuilder;
use CubeSystems\Leaf\Services\ModuleBuilder;
use CubeSystems\Leaf\Support\Facades\AdminModule;
use CubeSystems\Leaf\Support\Facades\LeafRouter;
use Illuminate\Foundation\AliasLoader;
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
     * @var ContentTypeRoutesRegister
     */
    protected $routes;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        AliasLoader::getInstance()->alias( 'LeafRouter', LeafRouter::class );
        AliasLoader::getInstance()->alias( 'AdminModule', AdminModule::class );
        AliasLoader::getInstance()->alias( 'LeafRouter', LeafRouter::class );

        $this->app->singleton( ContentTypeRegister::class, function ()
        {
            return new ContentTypeRegister();
        } );

        $this->app->singleton( 'leaf_module_builder', function( $app )
        {
            return new ModuleBuilder( $app['leaf.modules'] );
        } );

        $this->app->singleton( 'leaf_router', function ()
        {
            return $this->app->make( ContentTypeRoutesRegister::class );
        } );

        $this->app->singleton( 'leaf_page_builder', function( $app )
        {
            return new PageBuilder( $app->make( ContentTypeRegister::class ), $app[ 'leaf_router' ] );
        } );

        $this->routes = $this->app->make( 'leaf_router' );
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

        if( !$this->app->routesAreCached() && \Schema::hasTable('nodes') )
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
