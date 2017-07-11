<?php

namespace CubeSystems\Leaf\Services;

use CubeSystems\Leaf\Admin\Settings\SettingDefinition;
use Illuminate\Support\Collection;

class SettingRegistry
{
    /**
     * @var Collection
     */
    protected $settings;

    /**
     * SettingRegistry constructor.
     */
    public function __construct()
    {
        $this->settings = new Collection();
    }

    /**
     * @param SettingDefinition $definition
     * @return void
     */
    public function register( SettingDefinition $definition )
    {
        $this->settings->put( $definition->getKey(), $definition );
    }

    /**
     * @param string $key
     * @return SettingDefinition|null
     */
    public function find( string $key )
    {
        return $this->settings->get( $key );
    }

    /**
     * @param string $key
     * @return bool
     */
    public function contains( string $key )
    {
        return $this->settings->contains( $key );
    }

    /**
     * @return Collection
     */
    public function getSettings(): Collection
    {
        return $this->settings;
    }
}