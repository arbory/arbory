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
            $this->addPermissionOptions($fields);
        });

        $model = $form->getModel();

        $form->addEventListener('create.before', function () use ($model) {
            $model->slug = str_slug($model->name);
        });

        return $form;
    }

    /**
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
     * @param Form\FieldSet $fields
     */
    protected function addPermissionOptions(Form\FieldSet $fields)
    {
        /** @var Role $role */
        $role = $fields->getModel();

        $serializableGroup = new Form\Fields\GroupedSerializableMultiselect('permissions');
        $checkedValues = [];

        /** @var Module $module */
        foreach ($this->admin->modules()->all() as $key => $module) {
            $permissionsOptions = $this->getPermissionOptions($module, $role, $checkedValues);
            $serializableGroup->addValueGroup($module->name(), $permissionsOptions);
        }

        $serializableGroup->setValue($checkedValues);
        $fields->add($serializableGroup);
    }

    /**
     * @param Module $module
     * @param Role $role
     * @param array $checkedValues
     * @return array
     */
    protected function getPermissionOptions(Module $module, Role $role, array &$checkedValues): array
    {
        $permissionsOptions = $module->getPermissions($role);
        $permissionsOptions = $permissionsOptions->mapWithKeys(function (ModulePermission $permission) use (&$checkedValues, $module) {
            $permissionValue = $module->getControllerClass() . '.' . $permission->getName();
            if ($permission->isAllowed()) {
                $checkedValues[] = $permissionValue;
            }

            return [$permissionValue => $permission->getTranslation()];
        });

        return $permissionsOptions->toArray();
    }
}
