<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use Centaur\AuthManager;
use Centaur\Replies\Reply;
use CubeSystems\Leaf\Http\Requests\LoginRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use View;
use Illuminate\Routing\Controller as BaseController;

class SessionController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var AuthManager
     */
    protected $authManager;

    /**
     * @param AuthManager $authManager
     */
    public function __construct( AuthManager $authManager )
    {
        $this->middleware( 'sentinel.guest', [ 'except' => 'postLogout' ] );

        $this->authManager = $authManager;
    }

    /**
     * @param Request $request
     * @return View
     */
    public function getLogin( Request $request )
    {
        return view(
            'leaf::layout.login',
            [ 'input' => $request ]
        );
    }

    /**
     * @param LoginRequest $request
     * @return RedirectResponse|Response|Redirect
     */
    public function postLogin( LoginRequest $request )
    {
        $credentials = array_only( $request->get( 'user' ), [ 'email', 'password' ] );
        $remember = (bool) $request->get( 'remember', false );

        /* @var $result Reply */
        $result = $this->authManager->authenticate( $credentials, $remember );

        if( $result->isFailure() )
        {
            $redirect = redirect( route( 'admin.login.form' ) );

            /* @var $redirect RedirectResponse */

            return $redirect
                ->withInput()
                ->withErrors( [
                    'user.email' => $result->message
                ] );
        }
        else
        {
            $path = session()->pull( 'url.intended', route( 'admin.dashboard' ) );

            return $result->dispatch( $path );
        }
    }

    /**
     * @return Response|Redirect
     */
    public function postLogout()
    {
        /* @var $result Reply */
        $this->authManager->logout( null, null );

        return redirect( route( 'admin.login.form' ) );
    }
}
