<?php

namespace Arbory\Base\Providers;

use Arbory\Base\Rules\Totp;
use Arbory\Base\Services\Authentication\Helpers\TwoFactorAuth;
use Illuminate\Foundation\Application;
use Laragear\TwoFactor\Console\TwoFactorInstallCommand;
use Laragear\TwoFactor\Http\Middleware\ConfirmTwoFactorCode;
use Laragear\TwoFactor\Http\Middleware\RequireTwoFactorEnabled;

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

        $this->commands(TwoFactorInstallCommand::class);

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

    public function boot(): void
    {
        $this->loadViewsFrom(static::VIEWS, 'two-factor');
        $this->loadTranslationsFrom(static::LANG, 'two-factor');

        $this->withMiddleware(RequireTwoFactorEnabled::class)->as('2fa.enabled');
        $this->withMiddleware(ConfirmTwoFactorCode::class)->as('2fa.confirm');

        $this->withValidationRule('totp', Totp::class);

        if ($this->app->runningInConsole()) {
            $this->publishFiles();
        }
    }

    /**
     * Publish config and migrations files.
     *
     * @return void
     */
    protected function publishFiles(): void
    {
        $this->publishMigrations();

        $this->publishes([static::CONFIG => $this->app->configPath('two-factor.php')], 'config');
    }
}
