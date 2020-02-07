<?php

namespace Arbory\Base\Admin;

use Arbory\Base\Menu\Menu;
use Cartalyst\Sentinel\Sentinel;
use Arbory\Base\Services\AssetPipeline;
use Arbory\Base\Services\ModuleRegistry;
use Arbory\Base\Admin\Module\ModuleRoutesRegistry;

class Admin
{
    /**
     * @var Sentinel
     */
    protected $sentinel;

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
     * @param Sentinel $sentinel
     * @param Menu $menu
     * @param AssetPipeline $assets
     */
    public function __construct(Sentinel $sentinel, Menu $menu, AssetPipeline $assets)
    {
        $this->sentinel = $sentinel;
        $this->routes = new ModuleRoutesRegistry();
        $this->modules = new ModuleRegistry($this);
        $this->menu = $menu;
        $this->assets = $assets;
    }

    /**
     * @return \Cartalyst\Sentinel\Sentinel
     */
    public function sentinel()
    {
        return $this->sentinel;
    }

    /**
     * @return \Arbory\Base\Services\AssetPipeline
     */
    public function assets()
    {
        return $this->assets;
    }

    /**
     * @return \Arbory\Base\Admin\Module\ModuleRoutesRegistry
     */
    public function routes()
    {
        return $this->routes;
    }

    /**
     * @return \Arbory\Base\Services\ModuleRegistry
     */
    public function modules()
    {
        return $this->modules;
    }

    /**
     * @return \Arbory\Base\Menu\Menu
     */
    public function menu()
    {
        return $this->menu;
    }
}
