<?php namespace CubeSystems\Leaf\Http\Controllers;

use App\User;
use CubeSystems\Leaf\Fields\Text;
use CubeSystems\Leaf\Fields\Toolbox;
use CubeSystems\Leaf\Scheme;

/**
 * @package UsersController
 */
class UsersController extends AdminController
{
    /**
     * @var User
     */
    protected $resource = User::class;

    public function indexFields( Scheme $scheme )
    {
        $scheme->field( new Text( 'name' ) );
        $scheme->field( new Text( 'email' ) );
        $scheme->field( new Text( 'created_at' ) );
        $scheme->field( new Text( 'updated_at' ) );
        $scheme->field( new Toolbox( 'toolbox' ) );
    }

    /**
     * @param Scheme $scheme
     */
    public function formFields( Scheme $scheme )
    {
        $scheme->field( new Text( 'name' ) );
        $scheme->field( new Text( 'email' ) );
    }

}
