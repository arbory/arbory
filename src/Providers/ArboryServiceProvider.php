<?php

namespace Arbory\Base\Providers;

use Arbory\Base\Menu\Menu;
use Arbory\Base\Admin\Admin;
use Arbory\Base\Services\AssetPipeline;
use Illuminate\Support\ServiceProvider;
use Arbory\Base\Console\Commands\SeedCommand;
use Arbory\Base\Console\Commands\InstallCommand;
use Arbory\Base\Console\Commands\CreateUserCommand;
use Arbory\Base\Services\Authentication\Drivers\Sentinel;
use Arbory\Base\Services\Authentication\AuthenticationMethod;

/**
 * Class ArboryServiceProvider.
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

        $this->app->singleton(AuthenticationMethod::class, function () {
            return $this->app->make(Sentinel::class);
        });
    }

    /**
     * Publish configuration file.
     */
    private function registerResources()
    {
        $configFilename = __DIR__.'/../../config/arbory.php';

        $this->mergeConfigFrom($configFilename, 'arbory');

        $this->publishes([
            $configFilename => config_path('arbory.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../stubs/pages.stub' => base_path('/routes/pages.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../stubs/admin_routes.stub' => base_path('/routes/admin.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../resources/lang/' => base_path('resources/lang/vendor/arbory'),
        ], 'lang');

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'arbory');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'arbory');
    }

    /**
     * Register Arbory commands.
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
     * Register Arbory module registry.
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
