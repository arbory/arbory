<?php

namespace Arbory\Base\Http\Controllers\Admin;

<<<<<<< HEAD
use View;
=======
use Arbory\Base\Services\Authentication\AuthenticationMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
>>>>>>> 765e20c... Decouple Sentinel from Authentication
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
<<<<<<< HEAD
use Arbory\Base\Http\Requests\LoginRequest;
use Illuminate\Foundation\Bus\DispatchesJobs;
=======
>>>>>>> 765e20c... Decouple Sentinel from Authentication
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Arbory\Base\Services\Authentication\SecurityStrategy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SecurityController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var string
     */
    protected $loginView = 'arbory::controllers.security.login';

    /**
     * @var AuthenticationMethod
     */
    protected $security;

    /**
     * @param AuthenticationMethod $security
     */
<<<<<<< HEAD
    public function __construct(SecurityStrategy $security)
    {
        $this->middleware('arbory.admin_quest', ['except' => 'postLogout']);
=======
    public function __construct(AuthenticationMethod $security)
    {
        $this->middleware('arbory.admin_quest', [
            'except' => 'postLogout'
        ]);
>>>>>>> 765e20c... Decouple Sentinel from Authentication

        $this->security = $security;
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getLogin(Request $request)
    {
<<<<<<< HEAD
        return view(
            'arbory::controllers.security.login',
            ['input' => $request]
=======
        return view($this->loginView, [
                'input' => $request
            ]
>>>>>>> 765e20c... Decouple Sentinel from Authentication
        );
    }

    /**
     * @return RedirectResponse|Response|Redirect
     */
<<<<<<< HEAD
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
=======
    public function postLogin()
    {
        $request = $this->getFormRequest();

        $remember = (bool)$request->get('remember', false);

        return $this->attemptLogin($request->validated(), $remember);
    }


    /**
     * @param array $credentials
     * @param bool $remember
     * @return RedirectResponse
     */
    protected function attemptLogin(array $credentials, bool $remember): RedirectResponse
    {
        $success = $this->security->authenticate($credentials, $remember);

        if ($success) {
            return Redirect::to(session()->pull('url.intended', route('admin.login.form')));
        }

        return Redirect::route('admin.login.form')
            ->withInput()
            ->withErrors([
                'error' => trans('arbory::security.authentication_failed')
>>>>>>> 765e20c... Decouple Sentinel from Authentication
            ]);
    }

    /**
     * @return RedirectResponse
     */
    public function postLogout(): RedirectResponse
    {
<<<<<<< HEAD
        $this->security->logout(null, null);

        return redirect(route('admin.login.form'));
=======
        $this->security->logout();

        return Redirect::route('admin.login.form');
    }

    /**
     * @return FormRequest
     */
    protected function getFormRequest(): FormRequest
    {
        return app(get_class($this->security->getFormRequest()));
>>>>>>> 765e20c... Decouple Sentinel from Authentication
    }
}