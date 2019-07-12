<?php

namespace Arbory\Base\Http\Controllers\Admin;

use View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Arbory\Base\Http\Requests\LoginRequest;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Arbory\Base\Services\Authentication\SecurityStrategy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
    public function __construct(SecurityStrategy $security)
    {
        $this->middleware('arbory.admin_quest', ['except' => 'postLogout']);

        $this->security = $security;
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getLogin(Request $request)
    {
        return view(
            'arbory::controllers.security.login',
            ['input' => $request]
        );
    }

    /**
     * @param LoginRequest $request
     * @return RedirectResponse|Response|Redirect
     */
    public function postLogin(LoginRequest $request)
    {
        $credentials = array_only($request->get('user'), ['email', 'password']);
        $remember = (bool) $request->get('remember', false);

        $result = $this->security->authenticate($credentials, $remember);

        if ($result->isSuccess()) {
            return $result->dispatch(session()->pull('url.intended', route('admin.login.form')));
        }

        return redirect(route('admin.login.form'))
            ->withInput()
            ->withErrors([
                'user.email' => $result->getMessage(),
            ]);
    }

    /**
     * @return Response|Redirect
     */
    public function postLogout()
    {
        $this->security->logout(null, null);

        return redirect(route('admin.login.form'));
    }
}
