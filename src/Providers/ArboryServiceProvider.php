<?php

namespace Arbory\Base\Providers;

use Arbory\Base\Admin\Admin;
use Arbory\Base\Console\Commands\CreateUserCommand;
use Arbory\Base\Console\Commands\InstallCommand;
use Arbory\Base\Console\Commands\SeedCommand;
use Arbory\Base\Menu\Menu;
use Arbory\Base\Services\AssetPipeline;
use Arbory\Base\Services\Authentication\SessionSecurityService;
use Arbory\Base\Services\Authentication\SecurityStrategy;
use Illuminate\Support\ServiceProvider;

/**
 * Class ArboryServiceProvider
 * @package Arbory\Base
 */
class ArboryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerResources();
        $this->registerModuleRegistry();
        $this->registerCommands();
        $this->registerLocales();

        $this->app->singleton(SecurityStrategy::class, function () {
            return $this->app->make(SessionSecurityService::class);
        });
    }

    /**
     * Publish configuration file.
     */
    private function registerResources()
    {
        $configFilename = __DIR__ . '/../../config/arbory.php';

        $this->mergeConfigFrom($configFilename, 'arbory');

        $this->publishes([
            $configFilename => config_path('arbory.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/../../stubs/settings.stub' => config_path('settings.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/../../stubs/admin_routes.stub' => base_path('/routes/admin.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/../../resources/lang/' => base_path('resources/lang/vendor/arbory')
        ], 'lang');


        $this->loadMigrationsFrom( __DIR__ . '/../../database/migrations' );
        $this->loadViewsFrom( __DIR__ . '/../../resources/views', 'arbory' );
    }

    /**
     * Load admin routes and register middleware
     */
    private function registerRoutesAndMiddlewares()
    {
        /**
         * @var Router $router
         */
        $router = $this->app[ 'router' ];

        $router->middlewareGroup( 'admin', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            ArboryAdminHasAllowedIpMiddleware::class
        ] );

        $router->aliasMiddleware( 'arbory.admin_auth', ArboryAdminAuthMiddleware::class );
        $router->aliasMiddleware( 'arbory.admin_module_access', ArboryAdminModuleAccessMiddleware::class );
        $router->aliasMiddleware( 'arbory.admin_quest', ArboryAdminGuestMiddleware::class );
        $router->aliasMiddleware( 'arbory.admin_in_role', ArboryAdminInRoleMiddleware::class );
        $router->aliasMiddleware( 'arbory.admin_has_access', ArboryAdminHasAccessMiddleware::class );
        $router->aliasMiddleware( 'arbory.route_redirect', ArboryRouteRedirectMiddleware::class );
        $router->aliasMiddleware( 'arbory.admin_has_allowed_ip', ArboryAdminHasAllowedIpMiddleware::class );

        $this->app->booted( function( $app )
        {
            $app[ Kernel::class ]->prependMiddleware( ArboryRouteRedirectMiddleware::class );
        } );

        $this->registerAdminRoutes();
        $this->registerAppRoutes();
    }

    private function registerAdminRoutes()
    {
        $this->app['router']->group( [
            'as' => 'admin.',
            'middleware' => 'admin',
            'namespace' => '\Arbory\Base\Http\Controllers',
            'prefix' => config( 'arbory.uri' )
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
            'middleware' => [ 'admin', 'arbory.admin_auth', 'arbory.admin_module_access' ],
            'namespace' => '',
            'prefix' => config( 'arbory.uri' )
        ],function () use ($adminRoutes)
        {
            include $adminRoutes;
        } );
    }

    /**
     * Register Arbory commands
     */
    private function registerCommands()
    {
        $commands = [
            'arbory.seed' => SeedCommand::class,
            'arbory.create-user' => CreateUserCommand::class,
            'arbory.install' => InstallCommand::class,
        ];

        foreach ($commands as $containerKey => $commandClass) {
            $this->registerCommand($containerKey, $commandClass);
        }
    }

    /**
     * @param string $containerKey
     * @param string $commandClass
     * @return void
     */
    private function registerCommand(string $containerKey, string $commandClass)
    {
        $this->app->singleton($containerKey, function () use ($commandClass) {
            return $this->app->make($commandClass);
        });

        $this->commands($containerKey);
    }

    /**
     * Register Arbory module registry
     */
    private function registerModuleRegistry()
    {
        $this->app->singleton('arbory', function () {
            return new Admin(
                $this->app['sentinel'],
                new Menu(),
                new AssetPipeline()
            );
        });

        $this->app->singleton(Admin::class, function () {
            return $this->app['arbory'];
        });
    }

    /**
     * @return void
     */
    private function registerLocales()
    {
        config()->set('translator.locales', config('arbory.locales'));
        config()->set('translatable.locales', config('arbory.locales'));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['arbory', 'arbory.seed', 'arbory.modules', 'arbory.menu'];
    }
}
