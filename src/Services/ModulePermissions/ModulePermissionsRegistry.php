<?php

namespace Arbory\Base\Services\ModulePermissions;

use Arbory\Base\Auth\Roles\Role;
use Cartalyst\Sentinel\Users\UserInterface;
use Illuminate\Support\Collection;
use Cartalyst\Sentinel\Sentinel;

class ModulePermissionsRegistry
{
    public const DEFAULT_PERMISSIONS = [
        'index',
        'create',
        'edit',
        'show'
    ];

    private const LINKED_PERMISSIONS = [
        'destroy' => 'edit',
        'update' => 'edit',
        'store' => 'create'
    ];

    /**
     * @var Collection
     */
    private $modulePermissions;

    /**
     * @var string
     */
    private $controllerClass;

    /**
     * @var Sentinel
     */
    private $sentinel;

    /**
     * ModulePermissionsRegistry constructor.
     * @param string $controllerClass
     * @param Sentinel $sentinel
     */
    public function __construct(string $controllerClass, Sentinel $sentinel)
    {
        $this->controllerClass = $controllerClass;
        $this->sentinel = $sentinel;
        $this->setPermissions();
    }

    /**
     * @param string $permission
     * @param Role|null $role
     * @return bool
     */
    public function accessible(string $permission, ?Role $role = null): bool
    {
        $permission = $this->getUnderlyingPermission($permission);
        if (!$this->modulePermissions->has($permission)) {
            return true;
        }

        $permissionName = $this->controllerClass . '.' . $permission;

        if ($role) {
            return $role->hasAccess($permissionName);
        }

        return $this->sentinel->hasAccess($permissionName);
    }

    /**
     * @param Role|null $role
     * @return Collection
     */
    public function getPermissions(?Role $role = null): Collection
    {
        $this->modulePermissions->each(function (ModulePermission $permission) use ($role) {
            $permission->setAllowed($this->accessible($permission->getName(), $role));
        });

        return $this->modulePermissions;
    }

    /**
     * @param ModulePermission $permission
     */
    public function register(ModulePermission $permission)
    {
        $this->modulePermissions->put($permission->getName(), $permission);
    }

    /**
     * @param string $permission
     * @return string
     */
    protected function getUnderlyingPermission(string $permission): string
    {
        return self::LINKED_PERMISSIONS[$permission] ?? $permission;
    }

    /**
     * @return void
     */
    protected function setPermissions()
    {
        $permissions = collect(self::DEFAULT_PERMISSIONS);

        $this->modulePermissions = $permissions->mapWithKeys(function ($permission) {
            $permission = new ModulePermission($permission);
            return [$permission->getName() => $permission];
        });
    }
}