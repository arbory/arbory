<?php

namespace CubeSystems\Leaf\Providers;

use CubeSystems\Leaf\Nodes\ContentTypeRegister;
use CubeSystems\Leaf\Nodes\ContentTypeRoutesRegister;
use CubeSystems\Leaf\Nodes\Node;
use CubeSystems\Leaf\Repositories\NodesRepository;
use CubeSystems\Leaf\Services\Content\PageBuilder;
use CubeSystems\Leaf\Support\Facades\Admin;
use CubeSystems\Leaf\Support\Facades\LeafRouter;
use CubeSystems\Leaf\Support\Facades\Page;
use CubeSystems\Leaf\Support\Facades\Settings;
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
        AliasLoader::getInstance()->alias( 'Admin', Admin::class );
        AliasLoader::getInstance()->alias( 'Page', Page::class );
        AliasLoader::getInstance()->alias( 'Settings', Settings::class );

        $this->app->singleton( ContentTypeRegister::class, function ()
        {
            return new ContentTypeRegister();
        } );

        $this->app->singleton( 'leaf_router', function ()
        {
            return $this->app->make( ContentTypeRoutesRegister::class );
        } );

        $this->app->singleton( 'leaf_page_builder', function()
        {
            return new PageBuilder(
                $this->app->make( ContentTypeRegister::class ),
                $this->app->make( 'leaf_router' )
            );
        } );

        $this->routes = $this->app->make( 'leaf_router' );
    }

    /**
     * @return void
     */
    public function boot()
    {
        $this->app[ 'router' ]->group( [
            'middleware' => 'web',
            'namespace' => 'App\Http\Controllers',
        ], function()
        {
            include base_path( 'routes/pages.php' );
        } );

        if( !app()->runningInConsole() )
        {
            $this->app->booted( function()
            {
                $this->app->singleton( Node::class, function()
                {
                    return $this->routes->getCurrentNode();
                } );
            } );

            $this->routes->registerNodes();
        }
        elseif( !$this->app->routesAreCached() && \Schema::hasTable( 'nodes' ) )
        {
            $this->routes->registerNodes();
        }

        $this->detectCurrentLocaleFromRoute( $this->app[ 'router' ] );
        $this->purgeOutdatedRouteCache();
    }

    /**
     * @return void
     */
    protected function purgeOutdatedRouteCache()
    {
        if ( $this->app->routesAreCached() )
        {
            $path = $this->app->getCachedRoutesPath();
            $modified = \File::lastModified( $path );

            if ( $modified < ( new NodesRepository )->getLastUpdateTimestamp() )
            {
                \File::delete( $path );
            }
        }
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
