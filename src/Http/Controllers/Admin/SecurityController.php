<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Auth\Users\User;
use Arbory\Base\Http\Requests\LoginRequest;
use Arbory\Base\Services\Authentication\SecurityStrategy;
use Arbory\Base\Services\AuthReply\Reply;
use Arbory\Base\Services\SecurityService;
use Sentinel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use View;
use Illuminate\Routing\Controller as BaseController;

class SecurityController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var SecurityStrategy
     */
    protected $security;

    /**
     * @param SecurityStrategy $security
     */
    public function __construct( SecurityStrategy $security )
    {
        $this->middleware( 'arbory.admin_quest', [ 'except' => 'postLogout' ] );

        $this->security = $security;
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
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
        
        $user = Sentinel::findByCredentials(array_merge($credentials,[
            'provider' => User::PROVIDER
        ]));
        
        $result = $this->security->authenticate( $user, $remember );

        if( $result->isSuccess() )
        {
            return $result->dispatch( session()->pull( 'url.intended', route( 'admin.login.form' ) ) );
        }

        return redirect( route( 'admin.login.form' ) )
            ->withInput()
            ->withErrors( [
                'user.email' => $result->getMessage()
            ] );
    }

    /**
     * @return Response|Redirect
     */
    public function postLogout()
    {
        $this->security->logout( null, null );

        return redirect( route( 'admin.login.form' ) );
    }
}
