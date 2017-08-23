<?php

namespace Arbory\Base\Admin\Settings;

use Arbory\Base\Providers\SettingsServiceProvider;
use Arbory\Base\Services\SettingRegistry;
use Illuminate\Support\Collection;

class Settings
{
    /**
     * @var SettingRegistry
     */
    protected $settingRegistry;

    /**
     * @param SettingRegistry $settingRegistry
     */
    public function __construct(
        SettingRegistry $settingRegistry
    )
    {
        $this->settingRegistry = $settingRegistry;
        $this->settingRegistry->importFromDatabase();
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get( string $key, $default = null )
    {
        $definition = $this->settingRegistry->find( $key );

        if( $definition->isFile() )
        {
            return $definition->getModel()->file;
        }

        return $definition ? $definition->getValue() : $default;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has( string $key )
    {
        return (bool) $this->get( $key );
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param mixed $type
     * @return void
     */
    public function set( string $key, $value, string $type = null )
    {
        $definition = new SettingDefinition( $key, $value, $type );

        $this->settingRegistry->register( $definition );

        $definition->save();
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->settingRegistry->getSettings()->mapWithKeys( function( SettingDefinition $definition )
        {
            return [ $definition->getKey() => $definition->getValue() ];
        } );
    }
}
