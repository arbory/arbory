<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Auth\Users\User;
use Arbory\Base\Http\Requests\LoginRequest;
use Arbory\Base\Services\Authentication\SecurityStrategy;
use Arbory\Base\Services\AuthReply\Reply;
use Arbory\Base\Services\SecurityService;
use Illuminate\Support\Arr;
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
     * @var string
     */
    protected $loginView = 'arbory::layout.login';

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
            $this->loginView,
            [ 'input' => $request ]
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response|Redirect
     */
    public function postLogin( Request $request )
    {
        $this->validateLogin($request);
        $remember = (bool) $request->get( 'remember', false );
        
        $result = $this->security->authenticateUser( $this->findAuthUser($request), $remember );

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
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    protected function validateLogin( Request $request )
    {
        $request->validate((new LoginRequest())->rules());
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Cartalyst\Sentinel\Users\UserInterface|array|null
     */
    protected function findAuthUser( Request $request )
    {
        $credentials = Arr::only( $request->get( 'user' ), [ 'email', 'password' ] );
    
        return Sentinel::findByCredentials(array_merge($credentials, [
            'provider' => User::PROVIDER
        ]));
    }
}
