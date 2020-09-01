<?php

namespace Arbory\Base\Admin;

use Arbory\Base\Menu\Menu;
use Arbory\Base\Auth\Roles\Role;
use Cartalyst\Sentinel\Sentinel;
use Illuminate\Support\Collection;
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
     * @var bool
     */
    protected $authorized;

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

    /**
     * @return bool
     */
    public function isAuthorized()
    {
        if ($this->authorized === null) {
            $this->authorized = (bool) $this->sentinel()->check();
        }

        return $this->authorized;
    }

    /**
     * @param $module
     * @return bool
     */
    public function isAuthorizedFor($module)
    {
        if (! $this->isAuthorized()) {
            return false;
        }

        /**
         * @var Role[]|Collection
         */
        $roles = $this->sentinel()->getUser()->roles;

        $permissions = $roles->mapWithKeys(function (Role $role) {
            return $role->getPermissions();
        })->toArray();

        return in_array($module, $permissions, true);
    }
}
