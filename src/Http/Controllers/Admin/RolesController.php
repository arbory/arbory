<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Admin\Admin;
use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Traits\Crudify;
use Arbory\Base\Auth\Roles\Role;
use Arbory\Base\Admin\Module;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
            $fields->multipleSelect('permissions')->options($this->getPermissionOptions());
        });

        $model = $form->getModel();

        $form->addEventListener('validate.before', function (Request $request) {
            $resource = $request->input('resource');

            if (Arr::get($resource, 'permissions')) {
                return;
            }

            $request->merge(['resource' => array_merge($resource, ['permissions' => []])]);
        });

        $form->addEventListener('create.before', function () use ($model) {
            $model->slug = Str::slug($model->name);
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
     * @return Collection
     */
    protected function getPermissionOptions()
    {
        return $this->admin->modules()->mapWithKeys(function (Module $value) {
            return [$value->getControllerClass() => (string)$value];
        })->sort();
    }
}
