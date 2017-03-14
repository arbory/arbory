<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Admin\Form;
use CubeSystems\Leaf\Admin\Grid;
use CubeSystems\Leaf\Admin\Traits\Crudify;
use CubeSystems\Leaf\Admin\Form\Fields\BelongsToMany;
use CubeSystems\Leaf\Admin\Form\Fields\Password;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Html\Html;
use CubeSystems\Leaf\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Sentinel;

/**
 * Class UsersController
 * @package CubeSystems\Leaf\Http\Controllers\Admin
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
     */
    protected function form( Model $model )
    {
        $form = $this->module()->form( $model, function ( Form $form )
        {
            $form->addField( new Text( 'first_name' ) );
            $form->addField( new Text( 'last_name' ) );
            $form->addField( new Text( 'email' ) );
            $form->addField( new BelongsToMany( 'roles' ) );
            $form->addField( new Password( 'password' ) );
        } );

        $form->on( 'delete.before', function ( Form $form )
        {
            if( Sentinel::getUser()->getKey() === $form->getModel()->getKey() )
            {
                throw new \Exception( 'You cannot remove yourself!' );
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
                ->display( function ( Collection $value )
                {
                    return Html::ul(
                        $value->map( function ( $role )
                        {
                            return Html::li( (string) $role );
                        } )->toArray()
                    );
                } );
            $grid->column( 'created_at' );
            $grid->column( 'updated_at' );
        } );
    }

}

