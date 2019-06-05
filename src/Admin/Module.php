<?php

namespace Arbory\Base\Admin;

use Arbory\Base\Admin\Module\ResourceRoutes;
use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Arbory\Base\Auth\Roles\Role;
use Arbory\Base\Services\ModuleConfiguration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Arbory\Base\Services\ModulePermissions\ModulePermissionsRegistry;

/**
 * Class Module
 * @package Arbory\Base\Services
 */
class Module
{
    const AUTHORIZATION_TYPE_ROLES = 'roles';
    const AUTHORIZATION_TYPE_PERMISSIONS = 'permissions';
    const AUTHORIZATION_TYPE_NONE = 'none';
    const INDEX_PERMISSION_KEY = 'index';

    /**
     * @var Admin
     */
    protected $admin;

    /**
     * @var ModuleConfiguration
     */
    private $configuration;

    /**
     * @var ModulePermissionsRegistry
     */
    private $permissions;

    /**
     * @var ResourceRoutes
     */
    protected $routes;


    protected $breadcrumbs;


    /**
     * Module constructor.
     * @param Admin $admin
     * @param ModuleConfiguration $configuration
     * @param ModulePermissionsRegistry $permissions
     */
    public function __construct(Admin $admin, ModuleConfiguration $configuration, ModulePermissionsRegistry $permissions )
    {
        $this->admin = $admin;
        $this->configuration = $configuration;
        $this->permissions = $permissions;
    }

    public function __toString()
    {
        return $this->name();
    }

    /**
     * @return string
     */
    public function getControllerClass()
    {
        return $this->configuration->getControllerClass();
    }

    /**
     * @return ModuleConfiguration
     */
    public function getConfiguration(): ModuleConfiguration
    {
        return $this->configuration;
    }

    /**
     * @param string|null $permission
     * @return bool
     */
    public function isAuthorized(?string $permission = self::INDEX_PERMISSION_KEY): bool
    {
        return $this->permissions->accessible($permission);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isRequestAuthorized(Request $request): bool
    {
        $routeName = explode('.', $request->route()->getName());
        $routeName = end($routeName);

        return $this->isAuthorized($routeName);
    }

    /**
     * @return Breadcrumbs
     */
    public function breadcrumbs()
    {
        if( $this->breadcrumbs === null )
        {
            $this->breadcrumbs = new Breadcrumbs();  // TODO: Move this to menu
            $this->breadcrumbs->addItem( $this->name(), $this->url( 'index' ) );
        }

        return $this->breadcrumbs;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->getConfiguration()->getName();
    }

    /**
     * @param $route
     * @param array $parameters
     * @return string
     */
    public function url( $route, $parameters = [] )
    {
        if( $this->routes === null)
        {
            $this->routes = $this->admin->routes()->findByModule( $this );
        }

        return $this->routes->getUrl( $route, $parameters );
    }

    /**
     * @param Role|null $role
     * @return Collection
     */
    public function getPermissions(?Role $role = null): Collection
    {
        return $this->permissions->getPermissions($role);
    }

    /**
     * @param callable $callback
     * @return Module
     */
    public function registerCustomPermissions(callable $callback): Module
    {
        $callback($this->permissions);

        return $this;
    }
}
