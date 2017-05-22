<?php

namespace CubeSystems\Leaf\Providers;

use Cartalyst\Support\ServiceProvider;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Settings\Setting;
use CubeSystems\Leaf\Admin\Settings\SettingDefinition;
use CubeSystems\Leaf\Services\SettingRegistry;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * @var SettingRegistry
     */
    protected $settingRegistry;

    /**
     * @return void
     */
    public function register()
    {
        $this->app[ 'config' ]->set( 'settings', [] );

        $this->app->singleton( SettingRegistry::class, function()
        {
            return new SettingRegistry();
        } );

        $this->settingRegistry = $this->app[ SettingRegistry::class ];

        $this->remap( include config_path( 'settings.php' ) );

        Setting::all()->each( function( Setting $setting )
        {
            $definition = new SettingDefinition( $setting->name, $setting->value, $setting->type );
            $this->settingRegistry->register( $definition );

            $this->app[ 'config' ]->set( 'settings.' . $setting->name, $setting->value );
        } );
    }

    /**
     * @param array $properties
     * @param string $before
     * @return array
     */
    protected function remap( array $properties, $before = '' )
    {
        $results = [];

        foreach( $properties as $key => $data )
        {
            if( is_array( $data ) && !empty( $data ) && !array_key_exists( 'value', $data ) )
            {
                $results += $this->remap( $data, $before . $key . '.' );
            }
            else
            {
                $key = $before . $key;

                $type = $data[ 'type' ] ?? Text::class;;
                $value = $data[ 'value' ] ?? $data;

                $definition = new SettingDefinition( $key, $value, $type );
                $this->settingRegistry->register( $definition );

                $results[ $key ] = $definition;

                $this->app[ 'config' ]->set( 'settings.' . $key, $value );
            }
        }

        return $results;
    }
}