<?php

use Illuminate\Database\Migrations\Migration;
use Arbory\Base\Auth\Roles\Role;
use Arbory\Base\Services\Permissions\ModulePermissionsRegistry;

class UpdatePermissionsStructure extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        /** @var Role $role */
        foreach (Role::query()->get() as $role) {
            $role->setPermissions($this->transformPermissions($role));
            $role->save();
        }
    }

    /**
     * @param  Role  $role
     * @return array
     */
    private function transformPermissions(Role $role): array
    {
        $newPermissions = [];
        foreach ($role->getPermissions() as $controllerClass) {
            $newPermissions = array_merge($newPermissions, $this->getSplitControllerPermissions($controllerClass));
        }

        return $newPermissions;
    }

    /**
     * @param  string  $controllerClass
     * @return array
     */
    private function getSplitControllerPermissions(string $controllerClass): array
    {
        return collect(ModulePermissionsRegistry::DEFAULT_PERMISSIONS)
            ->mapWithKeys(function ($permission) use ($controllerClass) {
                return [$controllerClass . '.' . $permission => true];
            })->toArray();
    }
}
