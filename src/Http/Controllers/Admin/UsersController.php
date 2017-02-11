<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\CRUD\ResourceRoutes;
use CubeSystems\Leaf\Fields\BelongsToMany;
use CubeSystems\Leaf\Fields\Gravatar;
use CubeSystems\Leaf\Fields\Password;
use CubeSystems\Leaf\Fields\Text;
use CubeSystems\Leaf\Fields\Toolbox;
use CubeSystems\Leaf\FieldSet;
use CubeSystems\Leaf\Users\User;

class UsersController extends AdminController
{
//    use Crudify;

    protected $resource = User::class;

    public function indexFields()
    {
        $fieldSet = new FieldSet();
        $fieldSet->add( new Gravatar( 'email' ) )->setLabel( '' );
        $fieldSet->add( new Text( 'first_name' ) );
        $fieldSet->add( new Text( 'last_name' ) );
        $fieldSet->add( new Text( 'email' ) );
        $fieldSet->add( new BelongsToMany( 'roles' ) );
        $fieldSet->add( new Text( 'created_at' ) );
        $fieldSet->add( new Text( 'updated_at' ) );

        return $fieldSet;
    }

    public function formFields()
    {
        $fieldSet = new FieldSet();

        $fieldSet->add( new Text( 'first_name' ) );
        $fieldSet->add( new Text( 'last_name' ) );
        $fieldSet->add( new Text( 'email' ) );
        $fieldSet->add( new BelongsToMany( 'roles' ) );
        $fieldSet->add( new Password( 'password' ) );

        return $fieldSet;
    }

    public function dialog( $name )
    {
        $resourceId = request()->get( 'id' );

        $class = $this->resource;
        $node = $class::find( $resourceId );

        $routes = new ResourceRoutes( $this );

        $toolbox = new Toolbox;
        $toolbox->addItem( 'delete' )
            ->setUrl( $routes->getUrl( 'dialog', [
                'id' => $node->id,
                'dialog' => 'confirm_delete'
            ] ) );

        return $toolbox->renderMenu();
    }


}
