<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Admin;
use Illuminate\Http\Request;
use Arbory\Base\Auth\Users\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Arbory\Base\Admin\Traits\Crudify;

/**
 * Class UsersController.
 */
class UsersController extends Controller
{
    use Crudify;

    /**
     * @var string
     */
    protected $resource = User::class;

    /**
     * @var Admin
     */
    protected $admin;

    /**
     * UsersController constructor.
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
        $form->setFields(function (Form\FieldSet $fields, User $user) {
            $fields->text('first_name')->rules('required');
            $fields->text('last_name');
            $fields->text('email')->rules('required|unique:admin_users,email,'.$user->getKey());
            $fields->password('password')->rules('min:6|'.($user->exists ? 'nullable' : 'required'));
            $fields->checkbox('active')->setValue($this->getActivations()->completed($user));
            $fields->belongsToMany('roles');
        });

        $form->on('delete.before', function (Form $form) {
            if ($this->admin->sentinel()->getUser()->getKey() === $form->getModel()->getKey()) {
                throw new \InvalidArgumentException('You cannot remove yourself!');
            }
        });

        $model = $form->getModel();

        $form->addEventListener('create.before', function () use ($model) {
            unset($model->active);
        });

        $form->addEventListener('update.before', function (Request $request) use ($model) {
            if ($model->exists && ! $request->has('resource.password')) {
                $parameters = $request->except(['resource.password']);

                $request->request->replace($parameters);
            }
        });

        $form->addEventListeners(['update.before', 'create.after'], function (Request $request) use ($model) {
            unset($model->active);

            $active = $request->input('resource.active');

            if ($active && $this->getActivations()->completed($model)) {
                return;
            }

            if ($active) {
                $activation = $this->getActivations()->create($model);

                $this->getActivations()->complete($model, array_get($activation, 'code'));
            } else {
                $this->getActivations()->remove($model);
            }
        });

        return $form;
    }

    /**
     * @return Grid
     */
    public function grid(Grid $grid)
    {
        return $grid->setColumns(function (Grid $grid) {
            $grid->column('email', 'avatar')
                ->display(function ($value) {
                    return Html::span(
                        Html::image()->addAttributes([
                            'src' => '//www.gravatar.com/avatar/'.md5($value).'?d=retro',
                            'width' => 32,
                            'alt' => $value,
                        ])
                    );
                });
            $grid->column('email')->sortable();
            $grid->column('first_name');
            $grid->column('last_name');
            $grid->column('roles.name')
                ->display(function (Collection $value) {
                    return Html::ul(
                        $value->map(function ($role) {
                            return Html::li((string) $role);
                        })->toArray()
                    );
                });
            $grid->column('last_login');
        });
    }

    /**
     * @return \Cartalyst\Sentinel\Activations\ActivationRepositoryInterface
     */
    protected function getActivations()
    {
        return $this->admin->sentinel()->getActivationRepository();
    }
}
