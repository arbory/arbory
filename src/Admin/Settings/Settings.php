<?php

namespace CubeSystems\Leaf\Admin\Settings;

use CubeSystems\Leaf\Providers\SettingsServiceProvider;
use CubeSystems\Leaf\Services\SettingRegistry;
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

        /** @var SettingsServiceProvider $settingsService */
        $settingsService = \App::make( SettingsServiceProvider::class );
        $settingsService->importFromDatabase();
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get( string $key )
    {
        $definition = $this->settingRegistry->find( $key );

        return $definition ? $definition->getValue() : null;
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