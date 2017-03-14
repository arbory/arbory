<?php

namespace CubeSystems\Leaf\Providers;

use CubeSystems\Leaf\Nodes\Node;
use CubeSystems\Leaf\Nodes\Routing\Router;
use CubeSystems\Leaf\Repositories\NodesRepository;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use LeafRouter;

/**
 * Class NodeServiceProvider
 * @package CubeSystems\Leaf\Providers
 */
class NodeServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton( Router::class, function ( Application $app )
        {
            return new Router( $app, $app->make( NodesRepository::class ) );
        } );

        $this->app->singleton( NodesRepository::class, function ()
        {
            return new NodesRepository( Node::class );
        } );

        $this->app->singleton( 'leaf_router', function ( Application $app )
        {
            return new Router( $app );
        } );
    }

    /**
     *
     */
    public function boot()
    {
        if( !app()->runningInConsole() )
        {
            $this->app->booted( function ( Application $app )
            {
                LeafRouter::register( $app['request'] );

                $this->app->singleton( Node::class, function ()
                {
                    return LeafRouter::getCurrentNode();
                } );
            } );
        }

        app( 'router' )->matched( function ( RouteMatched $event )
        {
            // TODO: rewrite when i18n functionality is done

            $firstSegment = $event->request->segment( 1 );

            if( $firstSegment != config( 'leaf.uri' ) )
            {
                $this->app->setLocale( $firstSegment );
            }
        } );
    }
}
