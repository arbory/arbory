<?php

namespace Arbory\Base\Services\Permissions;

use Arbory\Base\Admin\Admin;
use Arbory\Base\Auth\Roles\Role;
use Illuminate\Support\Collection;
use Cartalyst\Sentinel\Sentinel;

class ModulePermissionsRegistry
{
    public const DEFAULT_PERMISSIONS = [
        'index',
        'create',
        'edit',
        'show',
    ];

    private const LINKED_PERMISSIONS = [
        'destroy' => 'edit',
        'update' => 'edit',
        'store' => 'create',
    ];

    private Collection $modulePermissions;

    private Sentinel $sentinel;

    /**
     * ModulePermissionsRegistry constructor.
     */
    public function __construct(private string $controllerClass)
    {
        /** @var Admin $admin */
        $admin = app(Admin::class);
        $this->sentinel = $admin->sentinel();
        $this->setPermissions();
    }

    public function accessible(string $permission, ?Role $role = null): bool
    {
        $permission = $this->getUnderlyingPermission($permission);
        if (! $this->modulePermissions->has($permission)) {
            return true;
        }

        $permissionName = $this->controllerClass . '.' . $permission;

        if ($role) {
            return $role->hasAccess($permissionName);
        }

        return $this->sentinel->hasAccess($permissionName);
    }

    public function getPermissions(?Role $role = null): Collection
    {
        $this->modulePermissions->each(function (ModulePermission $permission) use ($role) {
            $permission->setAllowed($this->accessible($permission->getName(), $role));
        });

        return $this->modulePermissions;
    }

    public function register(ModulePermission $permission)
    {
        $this->modulePermissions->put($permission->getName(), $permission);
    }

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
