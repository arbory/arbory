<?php

namespace CubeSystems\Leaf\Admin;

use Cartalyst\Sentinel\Sentinel;
use CubeSystems\Leaf\Admin\Module\ModuleRoutesRegistry;
use CubeSystems\Leaf\Auth\Roles\Role;
use CubeSystems\Leaf\Menu\Menu;
use CubeSystems\Leaf\Services\AssetPipeline;
use CubeSystems\Leaf\Services\ModuleRegistry;
use Illuminate\Support\Collection;

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
    public function __construct( Sentinel $sentinel, Menu $menu, AssetPipeline $assets )
    {
        $this->sentinel = $sentinel;
        $this->routes = new ModuleRoutesRegistry();
        $this->modules = new ModuleRegistry( $this );
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
     * @return \CubeSystems\Leaf\Services\AssetPipeline
     */
    public function assets()
    {
        return $this->assets;
    }

    /**
     * @return \CubeSystems\Leaf\Admin\Module\ModuleRoutesRegistry
     */
    public function routes()
    {
        return $this->routes;
    }

    /**
     * @return \CubeSystems\Leaf\Services\ModuleRegistry
     */
    public function modules()
    {
        return $this->modules;
    }

    /**
     * @return \CubeSystems\Leaf\Menu\Menu
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
        if( $this->authorized === null )
        {
            $this->authorized = (bool) $this->sentinel()->check();
        }

        return $this->authorized;
    }

    /**
     * @param $module
     * @return bool
     */
    public function isAuthorizedFor( $module )
    {
        if( !$this->isAuthorized() )
        {
            return false;
        }

        /**
         * @var $roles Role[]|Collection
         */
        $roles = $this->sentinel()->getUser()->roles;

        $permissions = $roles->mapWithKeys( function ( Role $role )
        {
            return $role->getPermissions();
        } )->toArray();

        return in_array( $module, $permissions, true );
    }
}
