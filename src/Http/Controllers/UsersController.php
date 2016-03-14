<?php namespace CubeSystems\Leaf\Http\Controllers;

use App\User;
use CubeSystems\Leaf\Fields\Text;
use CubeSystems\Leaf\Fields\Toolbox;
use CubeSystems\Leaf\FieldSet;

/**
 * @package UsersController
 */
class UsersController extends AdminController
{
    /**
     * @var User
     */
    protected $resource = User::class;

    public function indexFields( FieldSet $fieldSet )
    {
        $fieldSet->add( new Text( 'name' ) );
        $fieldSet->add( new Text( 'email' ) );
        $fieldSet->add( new Text( 'created_at' ) );
        $fieldSet->add( new Text( 'updated_at' ) );
        $fieldSet->add( new Toolbox( 'toolbox' ) );
    }

    /**
     * @param FieldSet $fieldSet
     */
    public function formFields( FieldSet $fieldSet )
    {
        $fieldSet->add( new Text( 'name' ) );
        $fieldSet->add( new Text( 'email' ) );
    }

}
