<?php namespace CubeSystems\Leaf\Http\Controllers;

use App\User;

/**
 * @package UsersController
 */
class UsersController extends Controller
{
    /**
     * @var User
     */
    protected $resource = User::class;

    /**
     * @var array
     */
    protected $indexFields = [ 'name', 'email', 'created_at' ];

}
