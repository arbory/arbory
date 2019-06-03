<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Arbory\Base\Auth\Roles\Role;
use Arbory\Base\Services\ModulePermissions\ModulePermissionsRegistry;

class UpdatePermissionsStructure extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        foreach (Role::get() as $role) {
            $newPermissions = [];
            foreach ($role->getPermissions() as $controllerClass) {
                $splitPermissions = collect(ModulePermissionsRegistry::DEFAULT_PERMISSIONS)
                    ->mapWithKeys(function ($permission) use ($controllerClass) {
                        return [$controllerClass . '.' . $permission => true];
                    })->toArray();

                $newPermissions = array_merge($newPermissions, $splitPermissions);
            }

            $role->setPermissions($newPermissions);
            $role->save();
        }
    }
}