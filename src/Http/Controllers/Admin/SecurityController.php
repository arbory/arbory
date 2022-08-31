<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Arbory\Base\Services\Authentication\AuthenticationMethod;

class SecurityController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(protected AuthenticationMethod $security)
    {
        $this->middleware('arbory.admin_quest', [
            'except' => 'postLogout',
        ]);
    }

    public function getLogin(Request $request): \Illuminate\View\View|\Illuminate\Contracts\View\Factory
    {
        return view($this->security->getLoginView(), [
            'input' => $request,
        ]);
    }

    public function postLogin(): \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Support\Facades\Redirect
    {
        $request = $this->getFormRequest();
        $remember = (bool) $request->get('remember', false);

        return $this->attemptLogin($request->validated(), $remember);
    }

    protected function attemptLogin(array $credentials, bool $remember): RedirectResponse
    {
        $success = $this->security->authenticate($credentials, $remember);

        if ($success) {
            return Redirect::to(session()->pull('url.intended', route('admin.login.form')));
        }

        return Redirect::route('admin.login.form')
            ->withInput()
            ->withErrors([
                'error' => trans('arbory::security.authentication_failed'),
            ]);
    }

    public function postLogout(): RedirectResponse
    {
        $this->security->logout();

        return Redirect::route('admin.login.form');
    }

    protected function getFormRequest(): FormRequest
    {
        return app($this->security->getFormRequest()::class);
    }
}
