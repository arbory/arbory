<?php

namespace CubeSystems\Leaf\Admin;

use CubeSystems\Leaf\Admin\Module\ModuleRoutesRegistry;
use CubeSystems\Leaf\Menu\Menu;
use CubeSystems\Leaf\Services\AssetPipeline;
use CubeSystems\Leaf\Services\ModuleRegistry;

class Admin
{
    /**
     * @var AssetPipeline
     */
    protected $assets;

    /**
     * @var ModuleRoutesRegistry
     */
    protected $routes;

    /**
     * @var ModuleRegistry
     */
    protected $modules;

    /**
     * @var Menu
     */
    protected $menu;

    /**
     * Admin constructor.
     * @param Menu $menu
     * @param AssetPipeline $assets
     */
    public function __construct( Menu $menu, AssetPipeline $assets )
    {
        $this->routes = new ModuleRoutesRegistry();
        $this->modules = new ModuleRegistry( $this );
        $this->menu = $menu;
        $this->assets = $assets;
    }

    /**
     * @return AssetPipeline
     */
    public function assets()
    {
        return $this->assets;
    }

    /**
     * @return ModuleRoutesRegistry
     */
    public function routes()
    {
        return $this->routes;
    }

    /**
     * @return ModuleRegistry
     */
    public function modules()
    {
        return $this->modules;
    }

    /**
     * @return Menu
     */
    public function menu()
    {
        return $this->menu;
    }
}
