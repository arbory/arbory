<?php

namespace Arbory\Base\Admin;

use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Arbory\Base\Admin\Module\ResourceRoutes;
use Arbory\Base\Auth\Roles\Role;
use Arbory\Base\Services\ModuleConfiguration;
use Arbory\Base\Services\Permissions\ModulePermission;
use Arbory\Base\Services\Permissions\ModulePermissionsRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class Module.
 */
class Module
{
    const AUTHORIZATION_TYPE_NONE = 'none';
    private const INDEX_PERMISSION_KEY = 'index';

    /**
     * @var Admin
     */
    protected $admin;

    /**
     * @var ModuleConfiguration
     */
    private $configuration;

    /**
     * @var ResourceRoutes
     */
    protected $routes;

    /**
     * @var Breadcrumbs
     */
    protected $breadcrumbs;

    /**
     * @var ModulePermissionsRegistry
     */
    private $permissions;

    /**
     * Module constructor.
     * @param Admin $admin
     * @param ModuleConfiguration $configuration
     * @param ModulePermissionsRegistry $permissions
     */
    public function __construct(
        Admin $admin,
        ModuleConfiguration $configuration,
        ModulePermissionsRegistry $permissions
    ) {
        $this->admin = $admin;
        $this->configuration = $configuration;
        $this->permissions = $permissions;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name();
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
     * @return Breadcrumbs
     */
    public function breadcrumbs()
    {
        if ($this->breadcrumbs === null) {
            $this->breadcrumbs = new Breadcrumbs();  // TODO: Move this to menu
            $this->breadcrumbs->addItem($this->name(), $this->url('index'));
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
    public function url($route, $parameters = [])
    {
        if ($this->routes === null) {
            $this->routes = $this->admin->routes()->findByModule($this);
        }

        return $this->routes->getUrl($route, $parameters);
    }

    /**
     * @param Role|null $role
     * @return Collection|ModulePermission[]
     */
    public function getPermissions(?Role $role = null): Collection
    {
        return $this->permissions->getPermissions($role);
    }

    /**
     * @param callable $callback
     * @return Module
     */
    public function registerCustomPermissions(callable $callback): self
    {
        $callback($this->permissions);

        return $this;
    }
}
