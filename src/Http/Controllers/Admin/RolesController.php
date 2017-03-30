<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Admin\Form;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Grid;
use CubeSystems\Leaf\Admin\Traits\Crudify;
use CubeSystems\Leaf\Auth\Roles\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

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
     * @param Model $model
     * @return Form
     */
    protected function form( Model $model )
    {
        return $this->module()->form( $model, function ( Form $form )
        {
            $permissions = [
                'users.create',
                'users.update',
                'users.view',
                'users.destroy',
                'roles.create',
                'roles.update',
                'roles.view',
                'roles.delete',
            ];

            $form->addField( new Text( 'name' ) );
        } );
    }

    /**
     * @return Grid
     */
    public function grid()
    {
        return $this->module()->grid( $this->resource(), function ( Grid $grid )
        {
            $grid->column( 'name' )->sortable();
            $grid->column( 'created_at' )->sortable();
            $grid->column( 'updated_at' );
        } );
    }
}
