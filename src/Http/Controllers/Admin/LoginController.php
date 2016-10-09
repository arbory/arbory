<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use App\User;
use Auth;
use CubeSystems\Leaf\Http\Requests\LoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Routing\Controller;

/**
 * Class LoginController
 * @package CubeSystems\Leaf\Http\Controllers
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectPath;

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware( 'guest', [ 'except' => 'getLogout' ] );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view( 'leaf::layout.login' );
    }

    /**
     * @param LoginRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login( LoginRequest $request )
    {
        $credentials = array_only( $request->get( 'user' ), [ 'email', 'password' ] );

        if( $this->hasTooManyLoginAttempts( $request ) )
        {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse( $request );
        }

        if( $this->guard()->attempt( $credentials, $request->has( 'remember' ) ) )
        {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts( $request );

        return redirect( route( 'admin.login' ) )
            ->withInput( $request->only( 'user.email', 'remember' ) )
            ->withErrors( [
                'user.email' => \Lang::get('auth.failed'),
            ] );
    }

    /**
     * @param LoginRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function authenticated( LoginRequest $request, User $user )
    {
        // TODO: Check permission

        return redirect( route( 'admin.dashboard' ) );
    }

     *
     */
    public function logout()
    {
        Auth::logout();

        return redirect( route( 'admin.login' ) );
    }
}
