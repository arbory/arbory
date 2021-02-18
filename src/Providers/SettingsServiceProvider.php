<?php

namespace Arbory\Base\Providers;

use Cartalyst\Support\ServiceProvider;
use Arbory\Base\Admin\Settings\Settings;
use Arbory\Base\Services\SettingRegistry;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->singleton(SettingRegistry::class, function () {
            return new SettingRegistry();
        });

        $this->app->singleton('arbory_settings', function () {
            return new Settings($this->app[SettingRegistry::class]);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $paths = [
            __DIR__.'/../../config/settings.php',
            config_path('settings.php'),
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                $this->app[SettingRegistry::class]->importFromConfig(include $path);
            }
        }
    }
}
