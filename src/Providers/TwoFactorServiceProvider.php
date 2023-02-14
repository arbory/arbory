<?php

namespace Arbory\Base\Providers;

use Arbory\Base\Services\Authentication\Helpers\TwoFactorAuth;
use Illuminate\Foundation\Application;

class TwoFactorServiceProvider extends \Laragear\TwoFactor\TwoFactorServiceProvider
{
    public const CONFIG = __DIR__ . '/../../config/two-factor.php';

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(static::CONFIG, 'two-factor');

        $this->app->bind(TwoFactorAuth::class, static function (Application $app): TwoFactorAuth {
            $config = $app->make('config');

            return new TwoFactorAuth(
                $app->make('auth'),
                $app->make('session.store'),
                $app->make('request'),
                $config->get('two-factor.login.view'),
                $config->get('two-factor.login.key'),
                $config->get('two-factor.login.flash')
            );
        });
    }

    /**
     * Publish config and migrations files.
     *
     * @return void
     */
    protected function publishFiles(): void
    {
        $this->publishesMigrations(static::DB);

        $this->publishes([static::CONFIG => $this->app->configPath('two-factor.php')], 'config');
    }
}
