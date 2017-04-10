<?php namespace CubeSystems\Leaf\Providers;

use CubeSystems\Leaf\Admin\Form\Fields\Checkbox;
use CubeSystems\Leaf\Admin\Form\Fields\DateTime;
use CubeSystems\Leaf\Admin\Form\Fields\Hidden;
use CubeSystems\Leaf\Admin\Form\Fields\LeafFile;
use CubeSystems\Leaf\Admin\Form\Fields\Richtext;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Form\Fields\Textarea;
use CubeSystems\Leaf\Console\Commands\GenerateCommand;
use CubeSystems\Leaf\Console\Commands\GeneratorCommand;
use CubeSystems\Leaf\Console\Commands\InstallCommand;
use CubeSystems\Leaf\Console\Commands\SeedCommand;
use CubeSystems\Leaf\Http\Middleware\LeafAdminAuthMiddleware;
use CubeSystems\Leaf\Http\Middleware\LeafAdminGuestMiddleware;
use CubeSystems\Leaf\Http\Middleware\LeafAdminHasAccessMiddleware;
use CubeSystems\Leaf\Http\Middleware\LeafAdminInRoleMiddleware;
use CubeSystems\Leaf\Menu\Menu;
use CubeSystems\Leaf\Services\FieldTypeRegistry;
use CubeSystems\Leaf\Services\ModuleRegistry;
use CubeSystems\Leaf\Services\StubRegistry;
use Dimsav\Translatable\TranslatableServiceProvider;
use File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Roboc\Glide\GlideImageServiceProvider;
use Route;
use Sentinel;
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
     * @return void
     */
    public function boot()
    {
        config()->set( 'translator.locales', config( 'translatable.locales' ) );

        $this->registerResources();
        $this->registerServiceProviders();
        $this->registerAliases();
        $this->registerModuleRegistry();
        $this->registerCommands();
        $this->registerRoutesAndMiddlewares();
        $this->registerFields();
        $this->registerGeneratorStubs();

        View::composer( '*layout*', function ( \Illuminate\View\View $view )
        {
            $view->with( 'user', Sentinel::getUser( true ) );
        } );

        $this->app->bind( 'leaf.menu', function ()
        {
            return new Menu(
                config( 'leaf.menu' )
            );
        }, true );
    }

    /**
     * Register related service providers
     */
    private function registerServiceProviders()
    {
        $this->app->register( LeafTranslationServiceProvider::class );
        $this->app->register( TranslatableServiceProvider::class );
        $this->app->register( LeafFileServiceProvider::class );
        $this->app->register( LeafAuthServiceProvider::class );
        $this->app->register( GlideImageServiceProvider::class );
    }

    /**
     * Register related aliases
     */
    private function registerAliases()
    {
        $aliasLoader = AliasLoader::getInstance();
        $aliasLoader->alias( 'TranslationCache', \Waavi\Translation\Facades\TranslationCache::class );
        $aliasLoader->alias( 'Activation', \Cartalyst\Sentinel\Laravel\Facades\Activation::class );
        $aliasLoader->alias( 'Reminder', \Cartalyst\Sentinel\Laravel\Facades\Reminder::class );
        $aliasLoader->alias( 'Sentinel', \Cartalyst\Sentinel\Laravel\Facades\Sentinel::class );
        $aliasLoader->alias( 'GlideImage', \Roboc\Glide\Support\Facades\GlideImage::class );
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
            __DIR__ . '/../../stubs/admin_routes.stub' => base_path( '/routes/admin.php' )
        ], 'config' );

        $this->loadMigrationsFrom( __DIR__ . '/../../database/migrations' );
        $this->loadViewsFrom( __DIR__ . '/../../resources/views', 'leaf' );
        $this->loadTranslationsFrom( __DIR__ . '/../../resources/lang', 'leaf' );
    }

    /**
     * Load admin routes and register middleware
     */
    private function registerRoutesAndMiddlewares()
    {
        $router = $this->app['router'];

        $router->middlewareGroup( 'admin', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ] );

        $router->aliasMiddleware( 'leaf.admin_auth', LeafAdminAuthMiddleware::class );
        $router->aliasMiddleware( 'leaf.admin_quest', LeafAdminGuestMiddleware::class );
        $router->aliasMiddleware( 'leaf.admin_in_role', LeafAdminInRoleMiddleware::class );
        $router->aliasMiddleware( 'leaf.admin_has_access', LeafAdminHasAccessMiddleware::class );

        $this->registerLeafRoutes();
        $this->registerAppRoutes();
    }

    private function registerLeafRoutes()
    {
        $this->app['router']->group( [
            'as' => 'admin.',
            'middleware' => 'admin',
            'namespace' => '\CubeSystems\Leaf\Http\Controllers',
            'prefix' => config( 'leaf.uri' )
        ], function ()
        {
            include __DIR__ . '/../../routes/admin.php';
        } );
    }

    private function registerAppRoutes()
    {
        $adminRoutes = base_path( 'routes/admin.php' );

        if( !File::exists( $adminRoutes ) )
        {
            return;
        }

        $this->app['router']->group( [
            'as' => 'admin.',
            'middleware' => [ 'admin', 'leaf.admin_auth' ],
            'namespace' => '',
            'prefix' => config( 'leaf.uri' )
        ], function () use ($adminRoutes)
        {
            include $adminRoutes;
        } );
    }

    /**
     * Register Leaf commands
     */
    private function registerCommands()
    {
        $this->app->singleton( 'leaf.seed', function ( $app )
        {
            return new SeedCommand( $app['db'] );
        } );

        $this->app->singleton( 'leaf.install', function ( $app )
        {
            return $this->app->make( InstallCommand::class );
        } );

        $this->app->singleton( 'leaf.generator', function ()
        {
            return $this->app->make( GeneratorCommand::class );
        } );

        $this->app->singleton( 'leaf.generate', function ()
        {
            return $this->app->make( GenerateCommand::class );
        } );

        $this->commands( 'leaf.seed' );
        $this->commands( 'leaf.install' );
        $this->commands( 'leaf.generator' );
        $this->commands( 'leaf.generate' );
    }

    /**
     * Register Leaf module registry
     */
    private function registerModuleRegistry()
    {
        $this->app->singleton( 'leaf.modules', function ( Application $app )
        {
            return new ModuleRegistry(
                $app->config['leaf.modules']
            );
        } );
    }

    /**
     * Register leaf fields
     */
    private function registerFields()
    {
        $this->app->singleton( FieldTypeRegistry::class, function ( Application $app )
        {
            $fieldTypeRegistry = new FieldTypeRegistry();

            $fieldTypeRegistry->registerByType( 'integer', Hidden::class, 'int' );
            $fieldTypeRegistry->registerByType( 'string', Text::class, 'string' );
            $fieldTypeRegistry->registerByType( 'text', Textarea::class, 'string' );
            $fieldTypeRegistry->registerByType( 'longtext', Richtext::class, 'string' );
            $fieldTypeRegistry->registerByType( 'datetime', DateTime::class, 'string' );
            $fieldTypeRegistry->registerByType( 'boolean', Checkbox::class, 'bool' );

            $fieldTypeRegistry->registerByRelation( 'leaf_files', LeafFile::class );
            $fieldTypeRegistry->registerByRelation( 'file', LeafFile::class );

            return $fieldTypeRegistry;
        } );
    }

    /**
     * Register stubs used by generators
     */
    private function registerGeneratorStubs()
    {
        $this->app->singleton( StubRegistry::class, function ( Application $app )
        {
            $stubRegistry = new StubRegistry();

            $stubRegistry->registerStubs(
                $app[ Filesystem::class ],
                base_path( 'vendor/cubesystems/leaf/stubs' )
            );

            return $stubRegistry;
        } );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ 'leaf.seed', 'leaf.modules', 'leaf.menu' ];
    }
}
