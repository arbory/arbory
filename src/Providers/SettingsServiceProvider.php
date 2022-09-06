<?php

namespace Arbory\Base\Providers;

use Arbory\Base\Admin\Settings\Settings;
use Arbory\Base\Services\SettingRegistry;
use Cartalyst\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->singleton(SettingRegistry::class, fn () => new SettingRegistry());

        $this->app->singleton('arbory_settings', fn () => new Settings($this->app[SettingRegistry::class]));
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $paths = [
            __DIR__ . '/../../config/settings.php',
            config_path('settings.php'),
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                $this->app[SettingRegistry::class]->importFromConfig(include $path);
            }
        }
    }
}
