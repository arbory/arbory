<?php namespace CubeSystems\Leaf\Providers;

use Composer\Factory;
use Composer\IO\BufferIO;
use CubeSystems\Leaf\Console\Commands\SeedCommand;
use CubeSystems\Leaf\Http\Middleware\LeafAdminAuthMiddleware;
use CubeSystems\Leaf\Http\Middleware\LeafAdminGuestMiddleware;
use CubeSystems\Leaf\Http\Middleware\LeafAdminHasAccessMiddleware;
use CubeSystems\Leaf\Http\Middleware\LeafAdminInRoleMiddleware;
use CubeSystems\Leaf\Menu\Menu;
use CubeSystems\Leaf\Services\ModuleRegistry;
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
        $aliasLoader = AliasLoader::getInstance();

        $this->app->register( LeafTranslationServiceProvider::class );
        $aliasLoader->alias( 'TranslationCache', \Waavi\Translation\Facades\TranslationCache::class );

        $this->registerComposerSingleton();
        $this->registerSentinelSingleton();
        $this->registerModuleRegistry();

        $this->loadViewsFrom( __DIR__ . '/../../resources/views', 'leaf' );
        $this->loadTranslationsFrom( __DIR__ . '/../../resources/lang', 'leaf' );

        $this->registerResources();
        $this->registerMigrations();

        $this->app->register( TranslatableServiceProvider::class );
        $this->app->register( LeafFileServiceProvider::class );
        $this->app->register( LeafSentinelServiceProvider::class );

        $aliasLoader->alias( 'Activation', \Cartalyst\Sentinel\Laravel\Facades\Activation::class );
        $aliasLoader->alias( 'Reminder', \Cartalyst\Sentinel\Laravel\Facades\Reminder::class );
        $aliasLoader->alias( 'Sentinel', \Cartalyst\Sentinel\Laravel\Facades\Sentinel::class );

        View::composer( '*layout*', function ( \Illuminate\View\View $view )
        {
            $view->with( 'user', Sentinel::getUser( true ) );
        } );

        $this->registerRoutesAndMiddlewares( $router );

        $this->registerCommands();
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
    private function registerResources()
    {
        $configFilename = __DIR__ . '/../../config/leaf.php';

        $this->mergeConfigFrom( $configFilename, 'leaf' );

        $this->publishes( [
            $configFilename => config_path( 'leaf.php' )
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

    /**
     *
     */
    private function registerComposerSingleton()
    {
        $this->app->singleton(
            \Composer\Composer::class,
            function ( Application $app )
            {
                $factory = new Factory();
                $io = new BufferIO( '', OutputInterface::VERBOSITY_QUIET, new OutputFormatter( false ) );
                $composerJsonFilename = realpath( $app->basePath() . '/' . Factory::getComposerFile() );
                $composer = $factory->createComposer( $io, $composerJsonFilename, false, $app->basePath(), true );

                return $composer;
            }
        );
    }

    /**
     *
     */
    private function registerSentinelSingleton()
    {
        $this->app->singleton(
            \Cartalyst\Sentinel\Sentinel::class,
            function ( Application $app )
            {
                return $app->make( 'sentinel' );
            }
        );
    }

    /**
     * @param Router $router
     */
    private function registerRoutesAndMiddlewares( Router $router )
    {
        $middleware = [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ];

        $router->aliasMiddleware( 'leaf.admin_auth', LeafAdminAuthMiddleware::class );
        $router->aliasMiddleware( 'leaf.admin_quest', LeafAdminGuestMiddleware::class );
        $router->aliasMiddleware( 'leaf.admin_in_role', LeafAdminInRoleMiddleware::class );
        $router->aliasMiddleware( 'leaf.admin_has_access', LeafAdminHasAccessMiddleware::class );

        // TODO: change group name to 'default' or something like that
        $router->middlewareGroup( 'admin', $middleware );

        $router->group( [
            'middleware' => 'admin',
            'namespace' => '\CubeSystems\Leaf\Http\Controllers',
            'prefix' => config( 'leaf.uri' )
        ], function ()
        {
            include __DIR__ . '/../../routes/admin.php';
        } );
    }

    /**
     *
     */
    private function registerCommands()
    {
        $this->app->singleton( 'leaf.seed', function ( $app )
        {
            return new SeedCommand( $app['db'] );
        } );

        $this->commands( 'leaf.seed' );
    }

    private function registerModuleRegistry()
    {
        $this->app->singleton( 'leaf.modules', function ( Application $app )
        {
            return new ModuleRegistry(
                $app->config['leaf.modules']
            );
        } );
    }
}
