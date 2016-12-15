<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Http\Requests\LoginRequest;
use CubeSystems\Leaf\Services\AuthReply\Reply;
use CubeSystems\Leaf\Services\AuthService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use View;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class SessionController
 * @package CubeSystems\Leaf\Http\Controllers\Admin
 */
class SessionController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * @param AuthService $authService
     */
    public function __construct( AuthService $authService )
    {
        $this->middleware( 'leaf.admin_quest', [ 'except' => 'postLogout' ] );

        $this->authService = $authService;
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
        $result = $this->authService->authenticate( $credentials, $remember );

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
        $this->authService->logout( null, null );

        return redirect( route( 'admin.login.form' ) );
    }
}
