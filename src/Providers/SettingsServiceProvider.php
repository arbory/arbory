<?php

namespace CubeSystems\Leaf\Providers;

use Cartalyst\Support\ServiceProvider;
use CubeSystems\Leaf\Admin\Settings\Setting;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom( config_path( 'settings.php' ), 'settings' );

        Setting::all()->each( function( Setting $setting )
        {
            $this->app[ 'config' ]->set( 'settings.' . $setting->name, [] );
            $this->app[ 'config' ]->set( 'settings.' . $setting->name . '.value', $setting->value );
            $this->app[ 'config' ]->set( 'settings.' . $setting->name . '.type', $setting->type );
        } );
    }
}