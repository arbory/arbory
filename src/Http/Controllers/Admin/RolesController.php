<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Admin;
use Arbory\Base\Admin\Layout\PanelLayout;
use Arbory\Base\Services\Permissions\ModulePermission;
use Illuminate\Http\Request;
use Arbory\Base\Admin\Module;
use Arbory\Base\Auth\Roles\Role;
use Illuminate\Routing\Controller;
use Arbory\Base\Admin\Traits\Crudify;
use Illuminate\Support\Str;
use Arbory\Base\Admin\Layout\Grid as LayoutGrid;

/**
 * Class RoleController.
 */
class RolesController extends Controller
{
    use Crudify;

    protected const PERMISSIONS_PREFIX = 'permissions.';
    protected const PERMISSION_FIELD_NAME = self::PERMISSIONS_PREFIX . '.%s.%s';
    protected const CHECKBOX_COLUMNS = 3;

    /**
     * @var string
     */
    protected $resource = Role::class;

    /**
     * @var Admin
     */
    protected $admin;

    /**
     * @var Request
     */
    protected $request;

    /**
     * RolesController constructor.
     * @param Admin $admin
     * @param Request $request
     */
    public function __construct(Admin $admin, Request $request)
    {
        $this->admin = $admin;
        $this->request = $request;
    }

    /**
     * @param Form $form
     * @param PanelLayout $layout
     * @return Form
     */
    protected function form(Form $form, PanelLayout $layout)
    {
        /** @var Role $role */
        $role = $form->getModel();

        $layout->panel($this->module->name(), $layout->fields(function (Form\FieldSet $fieldSet) {
            $fieldSet->text('name')->rules('required');
        }));

        foreach ($this->admin->modules()->all() as $module) {
            $layout->panel($module->name(), $this->getModuleFieldSet($layout, $module, $role));
        }

        $form->addEventListeners(['update.before', 'create.before'], function (Request $request) use ($form) {
            $this->setRolePermissions($request, $form);
        });

        $form->addEventListener('create.before', function () use ($role) {
            $role->slug = Str::slug($role->name);
        });

        return $form;
    }

    /**
     * @return array
     */
    public function layouts()
    {
        return [
            'grid' => Grid\Layout::class,
            'form' => PanelLayout::class,
        ];
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
     * @param Request $request
     * @param Form $form
     */
    protected function setRolePermissions(Request $request, Form $form): void
    {
        $permissionsInput = $request->input('resource.permissions');

        if (! $permissionsInput) {
            $permissionsInput = [];
        }

        $permissionsOutput = [];
        foreach ($permissionsInput as $moduleName => $permissions) {
            foreach ($permissions as $permissionName => $allowed) {
                $permissionsOutput[$moduleName . '.' . $permissionName] = (bool) $allowed;
            }
        }

        $role = $form->getModel();
        foreach (array_keys($role->getAttributes()) as $attribute) {
            if (Str::startsWith($attribute, self::PERMISSIONS_PREFIX)) {
                unset($role->{$attribute});
            }
        }

        $role->permissions = $permissionsOutput;
    }

    /**
     * @param PanelLayout $layout
     * @param Module $module
     * @param Role $role
     * @return LayoutGrid
     */
    protected function getModuleFieldSet(PanelLayout $layout, Module $module, Role $role)
    {
        return $layout->grid(function (LayoutGrid $grid) use ($module, $role, $layout) {
            foreach ($module->getPermissions($role) as $permission) {
                $fieldSetCallback = function (Form\FieldSet $fieldSet) use ($module, $permission) {
                    $fieldSet->add($this->getPermissionCheckbox($module, $permission));
                };
                $grid->column(self::CHECKBOX_COLUMNS, $layout->fields($fieldSetCallback));
            }
        });
    }

    /**
     * @param Module $module
     * @param ModulePermission $permission
     * @return Form\Fields\Checkbox
     */
    protected function getPermissionCheckbox(Module $module, ModulePermission $permission): Form\Fields\Checkbox
    {
        return (new Form\Fields\Checkbox($permission->getName()))
            ->setValue($this->isCreationRequest() || $permission->isAllowed())
            ->setName($this->getPermissionFieldName($module, $permission))
            ->setLabel(trans('arbory::permissions.' . $permission->getName()));
    }

    /**
     * @return bool
     */
    protected function isCreationRequest(): bool
    {
        return $this->request->url() === $this->module->url('create');
    }

    /**
     * @param Module $module
     * @param ModulePermission $permission
     * @return string
     */
    protected function getPermissionFieldName(Module $module, ModulePermission $permission): string
    {
        return sprintf(
            self::PERMISSION_FIELD_NAME,
            $module->getControllerClass(),
            $permission->getName()
        );
    }
}
