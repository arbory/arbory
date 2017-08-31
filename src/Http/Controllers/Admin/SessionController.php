<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Http\Requests\LoginRequest;
use Arbory\Base\Services\AuthReply\Reply;
use Arbory\Base\Services\AuthService;
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
 * @package Arbory\Base\Http\Controllers\Admin
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
        $this->middleware( 'arbory.admin_quest', [ 'except' => 'postLogout' ] );

        $this->authService = $authService;
    }

    /**
     * @param Request $request
     * @return View
     */
    public function getLogin( Request $request )
    {
        return view(
            'arbory::layout.login',
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
            $path = session()->pull( 'url.intended', route( 'admin.login.form' ) );

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
