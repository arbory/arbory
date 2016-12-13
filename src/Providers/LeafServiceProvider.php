<?php namespace CubeSystems\Leaf\Providers;

use Composer\Composer;
use Composer\Factory;
use Composer\IO\BufferIO;
use CubeSystems\Leaf\Http\Middleware\LeafAdminAuthMiddleware;
use CubeSystems\Leaf\Http\Middleware\LeafAdminGuestMiddleware;
use CubeSystems\Leaf\Http\Middleware\LeafAdminHasAccessMiddleware;
use CubeSystems\Leaf\Http\Middleware\LeafAdminInRoleMiddleware;
use CubeSystems\Leaf\Menu\Menu;
use Dimsav\Translatable\TranslatableServiceProvider;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Route;
use Sentinel;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Output\OutputInterface;
use View;

/**
 * Class LeafServiceProvider
 * @package CubeSystems\Leaf
 */
class LeafServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Router $router
     * @return void
     */
    public function boot( Router $router )
    {
        $this->loadViewsFrom( base_path( 'vendor/CubeSystems/Leaf/resources/views' ), 'leaf' );
        $this->loadTranslationsFrom( __DIR__ . '/../../resources/lang', 'leaf' );

        $this->publishResources();
        $this->registerMigrations();

        $this->app->register( TranslatableServiceProvider::class );
        $this->app->register( LeafFileServiceProvider::class );

        $this->app->singleton(
            \Composer\Composer::class,
            function ()
            {
                return Factory::create(
                    new BufferIO( '', OutputInterface::VERBOSITY_QUIET, new OutputFormatter( false ) )
                );
            }
        );

        $this->app->register( LeafSentinelServiceProvider::class );

        $loader = AliasLoader::getInstance();
        $loader->alias( 'Activation', \Cartalyst\Sentinel\Laravel\Facades\Activation::class );
        $loader->alias( 'Reminder', \Cartalyst\Sentinel\Laravel\Facades\Reminder::class );
        $loader->alias( 'Sentinel', \Cartalyst\Sentinel\Laravel\Facades\Sentinel::class );

        $this->app->singleton(
            \Cartalyst\Sentinel\Sentinel::class,
            function ( Application $app )
            {
                return $app->make( 'sentinel' );
            }
        );

        View::composer( '*layout*', function ( \Illuminate\View\View $view )
        {
            $view->with( 'user', Sentinel::getUser( true ) );
        } );

        $middleware = [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ];

        $router->middleware( 'leaf.admin_auth', LeafAdminAuthMiddleware::class );
        $router->middleware( 'leaf.admin_quest', LeafAdminGuestMiddleware::class );
        $router->middleware( 'leaf.admin_in_role', LeafAdminInRoleMiddleware::class );
        $router->middleware( 'leaf.admin_has_access', LeafAdminHasAccessMiddleware::class );

        // TODO: change group name to 'default' or something like that
        $router->middlewareGroup( 'admin', $middleware );

        Route::group( [
            'middleware' => 'admin',
            'namespace' => '\CubeSystems\Leaf\Http\Controllers',
            'prefix' => config( 'leaf.uri' )
        ], function ()
        {
            include __DIR__ . '/../../routes/admin.php';
        } );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( 'leaf.menu', function ()
        {
            return new Menu(
                config( 'leaf.menu' )
            );
        }, true );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array( 'Leaf' );
    }

    /**
     * Publish configuration file.
     */
    private function publishResources()
    {
        $this->publishes( [
            __DIR__ . '/../../config/leaf.php' => config_path( 'leaf.php' )
        ], 'config' );

        $this->publishes( [
            __DIR__ . '/../../gulpfile.js' => base_path( 'gulpfile.leaf.js' ),
        ], 'assets' );
    }

    /**
     *
     * Publish migration file.
     */
    private function registerMigrations()
    {
        /**
         * @var $migrator Migrator
         */
        $migrator = $this->app->make( 'migrator' );
        $migrator->path( __DIR__ . '/../../database/migrations' );
    }

}
