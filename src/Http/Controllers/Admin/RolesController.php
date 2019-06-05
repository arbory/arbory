<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Admin\Admin;
use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Traits\Crudify;
use Arbory\Base\Auth\Roles\Role;
use Arbory\Base\Admin\Module;
use Arbory\Base\Services\ModulePermissions\ModulePermission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Arbory\Base\Admin\Form\Fields\GroupedSerializableMultiselect;

/**
 * Class RoleController
 * @package App\Http\Controllers
 */
class RolesController extends Controller
{
    use Crudify;

    /**
     * @var string
     */
    protected $resource = Role::class;

    /**
     * @var Admin
     */
    protected $admin;

    /**
     * RolesController constructor.
     * @param Admin $admin
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    /**
     * @param Form $form
     * @return Form
     */
    protected function form(Form $form)
    {
        $form->setFields(function (Form\FieldSet $fields) {
            $fields->text('name')->rules('required');
            $fields->add($this->getPermissionsField($fields->getModel()));
        });

        $model = $form->getModel();

        $form->addEventListener('create.before', function () use ($model) {
            $model->slug = str_slug($model->name);
        });

        return $form;
    }

    /**
     * @param Grid $grid
     * @return Grid
     */
    public function grid(Grid $grid)
    {
        return $grid->setColumns(function (Grid $grid) {
            $grid->column('name')->sortable();
            $grid->column('created_at')->sortable();
            $grid->column('updated_at');
        });
    }

    /**
     * @param Role $role
     * @return GroupedSerializableMultiselect
     */
    protected function getPermissionsField(Role $role): GroupedSerializableMultiselect
    {
        $serializableGroup = new GroupedSerializableMultiselect('permissions');
        $checkedValues = [];

        /** @var Module $module */
        foreach ($this->admin->modules()->all() as $key => $module) {
            $permissionsOptions = $this->getPermissionOptions($module, $role);
            $serializableGroup->addValueGroup($module->name(), $permissionsOptions);
            $checkedValues = array_merge($checkedValues, $this->getActiveModulePermissions($module, $role));
        }

        $serializableGroup->setValue($checkedValues);

        return $serializableGroup;
    }

    /**
     * @param Module $module
     * @param Role $role
     * @return array
     */
    protected function getPermissionOptions(Module $module, Role $role): array
    {
        $permissionsOptions = $module->getPermissions($role);
        $permissionsOptions = $permissionsOptions->mapWithKeys(function (ModulePermission $permission) use ($module) {
            $permissionName = $this->getPermissionValueName($module, $permission);
            return [$permissionName => $permission->getTranslation()];
        });

        return $permissionsOptions->toArray();
    }

    /**
     * @param Module $module
     * @param Role $role
     * @return array
     */
    protected function getActiveModulePermissions(Module $module, Role $role): array
    {
        return $module->getPermissions($role)
            ->filter(function (ModulePermission $permission) {
                return $permission->isAllowed();
            })
            ->map(function (ModulePermission $permission) use ($module) {
                return $this->getPermissionValueName($module, $permission);
            })
            ->values()
            ->toArray();
    }

    /**
     * @param Module $module
     * @param ModulePermission $permission
     * @return string
     */
    protected function getPermissionValueName(Module $module, ModulePermission $permission): string
    {
        return $module->getControllerClass() . '.' . $permission->getName();
    }
}
