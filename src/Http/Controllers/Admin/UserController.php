<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Fields\BelongsToMany;
use CubeSystems\Leaf\Fields\Gravatar;
use CubeSystems\Leaf\Fields\Password;
use CubeSystems\Leaf\Fields\Text;
use CubeSystems\Leaf\Fields\Toolbox;
use CubeSystems\Leaf\FieldSet;
use CubeSystems\Leaf\Users\User;
use Illuminate\Database\Eloquent\Model;
use Sentinel;

/**
 * Class UserController
 * @package CubeSystems\Leaf\Http\Controllers\Admin
 */
class UserController extends AbstractCrudController
{
    protected $resource = User::class;

    /**
     * @param FieldSet $fieldSet
     */
    public function indexFields( FieldSet $fieldSet )
    {
        $fieldSet->add( new Gravatar( 'email' ) )->setLabel( '' );
        $fieldSet->add( new Text( 'first_name' ) );
        $fieldSet->add( new Text( 'last_name' ) );
        $fieldSet->add( new Text( 'email' ) );
        $fieldSet->add( new BelongsToMany( 'roles' ) );
        $fieldSet->add( new Text( 'created_at' ) );
        $fieldSet->add( new Text( 'updated_at' ) );
        $fieldSet->add( new Toolbox( 'toolbox' ) );
    }

    /**
     * @param FieldSet $fieldSet
     */
    public function formFields( FieldSet $fieldSet )
    {
        $fieldSet->add( new Text( 'first_name' ) );
        $fieldSet->add( new Text( 'last_name' ) );
        $fieldSet->add( new Text( 'email' ) );
        $fieldSet->add( new BelongsToMany( 'roles' ) );
        $fieldSet->add( new Password( 'password' ) );
    }

    /**
     * @param Model $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleting( Model $user )
    {
        if( Sentinel::getUser()->id == $user->id )
        {
            session()->flash( 'error', 'You cannot remove yourself!' );
        }

        $user->delete();
    }
}
