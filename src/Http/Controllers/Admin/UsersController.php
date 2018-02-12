<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Activation;
use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Form\Fields\Boolean;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Traits\Crudify;
use Arbory\Base\Admin\Form\Fields\BelongsToMany;
use Arbory\Base\Admin\Form\Fields\Password;
use Arbory\Base\Admin\Form\Fields\Text;
use Arbory\Base\Html\Html;
use Arbory\Base\Auth\Users\User;
use Arbory\Base\Http\Requests\UpdateUserRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Sentinel;

/**
 * Class UsersController
 * @package Arbory\Base\Http\Controllers\Admin
 */
class UsersController extends Controller
{
    use Crudify;

    /**
     * @var string
     */
    protected $resource = User::class;

    /**
     * @param Model $model
     * @return Form
     * @throws \InvalidArgumentException
     */
    protected function form( Model $model )
    {
        $form = $this->module()->form( $model, function ( Form $form ) use ( $model )
        {
            $form->addField( new Text( 'first_name' ) )->rules('required');
            $form->addField( new Text( 'last_name' ) )->rules('required');
            $form->addField( new Text( 'email' ) )->rules('required|unique:admin_users,email,' . $model->getKey());
            $form->addField( new Password( 'password' ) )->rules('min:6|' . ( $model->exists ? 'nullable' : 'required' ));
            $form->addField( new Boolean( 'active' ) )->setValue( Activation::completed( $model ) );
            $form->addField( new BelongsToMany( 'roles' ) );
        } );

        $form->on( 'delete.before', function ( Form $form )
        {
            if( Sentinel::getUser()->getKey() === $form->getModel()->getKey() )
            {
                throw new \InvalidArgumentException( 'You cannot remove yourself!' );
            }
        } );

        $form->addEventListener('create.before', function() use ( $model )
        {
            unset( $model->active );
        } );

        $form->addEventListener( 'update.before', function( Request $request ) use ( $model )
        {
            if( $model->exists && !$request->has( 'resource.password' ) )
            {
                $parameters = $request->except( [ 'resource.password' ] );

                $request->request->replace( $parameters );
            }
        } );

        $form->addEventListeners( [ 'update.before', 'create.after' ], function( Request $request ) use ( $model )
        {
            unset( $model->active );

            $active = $request->input( 'resource.active' );

            if( $active && Activation::completed( $model ) )
            {
                return;
            }

            if( $active )
            {
                $activation = Activation::create( $model );

                Activation::complete( $model, array_get( $activation, 'code' ) );
            }
            else
            {
                Activation::remove( $model );
            }
        } );

        return $form;
    }

    /**
     * @return Grid
     */
    public function grid()
    {
        return $this->module()->grid( $this->resource(), function ( Grid $grid )
        {
            $grid->column( 'email', 'avatar' )
                ->display( function ( $value )
                {
                    return Html::span(
                        Html::image()->addAttributes( [
                            'src' => '//www.gravatar.com/avatar/' . md5( $value ) . '?d=retro',
                            'width' => 32,
                            'alt' => $value,
                        ] )
                    );
                } );
            $grid->column( 'email' )->sortable();
            $grid->column( 'first_name' );
            $grid->column( 'last_name' );
            $grid->column( 'email' );
            $grid->column( 'roles.name' )
                ->display( function( Collection $value )
                {
                    return Html::ul(
                        $value->map( function( $role )
                        {
                            return Html::li( (string) $role );
                        } )->toArray()
                    );
                } );
            $grid->column( 'last_login' );
        } );
    }

}

