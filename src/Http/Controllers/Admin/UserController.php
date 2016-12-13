<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use Cartalyst\Sentinel\Roles\RoleInterface;
use CubeSystems\Leaf\Services\AuthReply\Reply;
use CubeSystems\Leaf\Services\AuthService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Mail;
use Sentinel;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Users\IlluminateUserRepository;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class UserController
 * @package CubeSystems\Leaf\Http\Controllers\Admin
 */
class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var IlluminateUserRepository
     */
    protected $userRepository;

    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * @param AuthService $authService
     */
    public function __construct( AuthService $authService )
    {
        $this->userRepository = app()->make( 'sentinel.users' );
        $this->authService = $authService;
    }

    /**
     * Display a listing of the users.
     *
     * @return Response
     */
    public function index()
    {
        $users = $this->userRepository->createModel()->with( 'roles' )->paginate( 15 );

        return view( 'leaf::controllers.users.index', [ 'users' => $users ] );
    }

    /**
     * @return Response
     */
    public function create()
    {
        $roles = app()->make( 'sentinel.roles' )->createModel()->all();

        return view( 'leaf::controllers.users.create', [ 'roles' => $roles ] );
    }

    /**
     * @param  Request $request
     * @return Response
     */
    public function store( Request $request )
    {
        $this->validate( $request, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ] );

        $credentials = [
            'email' => trim( $request->get( 'email' ) ),
            'password' => $request->get( 'password' ),
            'first_name' => $request->get( 'first_name', null ),
            'last_name' => $request->get( 'last_name', null )
        ];

        $activate = (bool) $request->get( 'activate', false );

        $result = $this->authService->register( $credentials, $activate );
        /* @var $result Reply */

        if( $result->isFailure() )
        {
            return $result->dispatch;
        }

        if( !$activate )
        {
            $code = $result->activation->getCode();
            $email = $result->user->email;
            Mail::queue(
                'leaf.email.welcome',
                [ 'code' => $code, 'email' => $email ],
                function ( $message ) use ( $email )
                {
                    $message->to( $email )
                        ->subject( 'Your account has been created' );
                }
            );
        }

        foreach( $request->get( 'roles', [] ) as $slug => $id )
        {
            /** @noinspection PhpUndefinedMethodInspection */
            $role = Sentinel::findRoleBySlug( $slug );
            if( $role )
            {
                /* @var $role RoleInterface */
                $role->users()->attach( $result->user );
            }
        }

        $result->setMessage( "User {$request->get('email')} has been created." );

        return $result->dispatch( route( 'admin.users.index' ) );
    }

    /**
     * @return Response
     */
    public function show()
    {
        return redirect( route( 'admin.users.index' ) );
    }

    /**
     * @param int $id
     * @return Response
     */
    public function edit( $id )
    {
        $user = $this->userRepository->findById( $id );

        $roles = app()->make( 'sentinel.roles' )->createModel()->all();

        if( $user )
        {
            return view( 'leaf::controllers.users.edit', [
                'user' => $user,
                'roles' => $roles
            ] );
        }

        session()->flash( 'error', 'Invalid user.' );

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update( Request $request, $id )
    {
        $this->validate( $request, [
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'confirmed|min:6',
        ] );

        $attributes = [
            'email' => trim( $request->get( 'email' ) ),
            'first_name' => $request->get( 'first_name', null ),
            'last_name' => $request->get( 'last_name', null )
        ];

        if( $request->has( 'password' ) )
        {
            $attributes['password'] = $request->get( 'password' );
        }

        $user = $this->userRepository->findById( $id );
        if( !$user )
        {
            if( $request->ajax() )
            {
                return response()->json( "Invalid user.", 422 );
            }
            session()->flash( 'error', 'Invalid user.' );

            return redirect()->back()->withInput();
        }

        $user = $this->userRepository->update( $user, $attributes );

        $roleIds = array_values( $request->get( 'roles', [] ) );
        $user->roles()->sync( $roleIds );

        if( $request->ajax() )
        {
            return response()->json( [ 'user' => $user ], 200 );
        }

        session()->flash( 'success', "{$user->email} has been updated." );

        return redirect( route( 'admin.users.index' ) );
    }

    /**
     * Remove the specified user from storage.
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function destroy( Request $request, $id )
    {
        $user = $this->userRepository->findById( $id );

        // Check to be sure user cannot delete himself
        if( Sentinel::getUser()->id == $user->id )
        {
            $message = 'You cannot remove yourself!';

            if( $request->ajax() )
            {
                return response()->json( $message, 422 );
            }
            session()->flash( 'error', $message );

            return redirect( route( 'admin.users.index' ) );
        }

        $user->delete();

        $message = "{$user->email} has been removed.";
        if( $request->ajax() )
        {
            return response()->json( [ $message ], 200 );
        }

        session()->flash( 'success', $message );

        return redirect( route( 'admin.users.index' ) );
    }
}
