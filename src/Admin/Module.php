<?php

namespace Arbory\Base\Admin;

use Arbory\Base\Admin\Module\ResourceRoutes;
use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Arbory\Base\Auth\Roles\Role;
use Arbory\Base\Services\ModuleConfiguration;
use Arbory\Base\Services\Permissions\ModulePermissionsRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class Module.
 */
class Module
{
    public const AUTHORIZATION_TYPE_NONE = 'none';
    private const INDEX_PERMISSION_KEY = 'index';

    /**
     * @var ResourceRoutes
     */
    protected $routes;

    /**
     * @var Breadcrumbs
     */
    protected $breadcrumbs;

    /**
     * Module constructor.
     */
    public function __construct(protected Admin $admin, private ModuleConfiguration $configuration, private ModulePermissionsRegistry $permissions)
    {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name();
    }

    public function isAuthorized(?string $permission = self::INDEX_PERMISSION_KEY): bool
    {
        return $this->permissions->accessible($permission);
    }

    public function isRequestAuthorized(Request $request): bool
    {
        $routeName = explode('.', $request->route()->getName());
        $routeName = end($routeName);

        return $this->isAuthorized($routeName);
    }

    public function getControllerClass(): string
    {
        return $this->configuration->getControllerClass();
    }

    public function getConfiguration(): ModuleConfiguration
    {
        return $this->configuration;
    }

    public function breadcrumbs(): Breadcrumbs
    {
        if ($this->breadcrumbs === null) {
            $this->breadcrumbs = new Breadcrumbs();  // TODO: Move this to menu
            $this->breadcrumbs->addItem($this->name(), $this->url('index'));
        }

        return $this->breadcrumbs;
    }

    public function name(): string
    {
        return $this->getConfiguration()->getName();
    }

    public function url(string $route, array $parameters = []): string
    {
        if ($this->routes === null) {
            $this->routes = $this->admin->routes()->findByModule($this);
        }

        return $this->routes->getUrl($route, $parameters);
    }

    public function getPermissions(?Role $role = null): Collection
    {
        return $this->permissions->getPermissions($role);
    }

    public function registerCustomPermissions(callable $callback): self
    {
        $callback($this->permissions);

        return $this;
    }
}
