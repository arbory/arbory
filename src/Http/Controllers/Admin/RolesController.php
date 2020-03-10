<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Admin\Form\Fields\Checkbox;
use Arbory\Base\Admin\Form\Fields\EmptyField;
use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Admin;
use Arbory\Base\Admin\Layout\PanelLayout;
use Arbory\Base\Html\Html;
use Arbory\Base\Services\Permissions\ModulePermission;
use Arbory\Base\Support\Models\PropertyRemover;
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
    protected const PERMISSION_FIELD_NAME = self::PERMISSIONS_PREFIX . '%s.%s';
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
     * @var
     */
    protected $propertyRemover;

    /**
     * RolesController constructor.
     * @param Admin $admin
     * @param Request $request
     * @param PropertyRemover $propertyRemover
     */
    public function __construct(Admin $admin, Request $request, PropertyRemover $propertyRemover)
    {
        $this->admin = $admin;
        $this->request = $request;
        $this->propertyRemover = $propertyRemover;
    }

    /**
     * @param Form $form
     * @param PanelLayout $layout
     * @return Form
     * @throws \Exception
     */
    protected function form(Form $form, PanelLayout $layout)
    {
        $assetPipeline = $this->admin->assets();
        $assetPipeline->js($assetPipeline->getMixUrl('/js/controllers/roles.js'));

        /** @var Role $role */
        $role = $form->getModel();

        $layout->panel($this->module->name(), $layout->fields(function (FieldSet $fieldSet) {
            $fieldSet->text('name')->rules('required');
            $fieldSet->add($this->getSelectionField());
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
        $role = $form->getModel();
        $role = $this->propertyRemover->remove($role, self::PERMISSIONS_PREFIX);

        $role->permissions = $this->getPermissions($request);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getPermissions(Request $request): array
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

        return $permissionsOutput;
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
                $fieldSetCallback = function (FieldSet $fieldSet) use ($module, $permission) {
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
        return (new Checkbox($permission->getName()))
            ->setValue($permission->isAllowed())
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

    /**
     * @return Form\Fields\EmptyField
     */
    protected function getSelectionField(): Form\Fields\EmptyField
    {
        $selectAllButton = Html::link(trans('arbory::permissions.select_all'))
            ->addClass('button primary')
            ->addAttributes(['id' => 'permissions_select_all']);

        $selectNoneButton = Html::link(trans('arbory::permissions.select_none'))
            ->addClass('button secondary')
            ->addAttributes(['id' => 'permissions_select_none']);

        return (new EmptyField())
            ->append($selectAllButton)
            ->append($selectNoneButton);
    }
}
