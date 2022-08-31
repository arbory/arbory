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
     * @var ModuleRoutesRegistry
     */
    protected $routes;

    /**
     * @var ModuleRegistry
     */
    protected $modules;

    /**
     * Admin constructor.
     */
    public function __construct(protected Sentinel $sentinel, protected Menu $menu, protected AssetPipeline $assets)
    {
        $this->routes = new ModuleRoutesRegistry();
        $this->modules = new ModuleRegistry($this);
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
