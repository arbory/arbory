<?php

namespace Arbory\Base\Providers;

use Cartalyst\Support\ServiceProvider;
use Arbory\Base\Admin\Settings\Settings;
use Arbory\Base\Services\SettingRegistry;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->app->singleton( SettingRegistry::class, function()
        {
            return new SettingRegistry();
        } );

        $this->app->singleton( 'arbory_settings', function()
        {
            return new Settings( $this->app[ SettingRegistry::class ] );
        } );
    }

    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        $settingsPath = config_path( 'settings.php' );

        if( file_exists( $settingsPath ) )
        {
            $this->app[ SettingRegistry::class ]->importFromConfig( include $settingsPath );
        }
    }
}
