<?php

namespace Arbory\Base\Http\Controllers\Admin;

use Arbory\Base\Services\Authentication\AuthenticationMethod;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Redirect;

class SecurityController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(protected AuthenticationMethod $security)
    {
        $this->middleware('arbory.admin_quest', [
            'except' => 'postLogout',
        ]);
    }

    public function getLogin(Request $request): View
    {
        return view($this->security->getLoginView(), [
            'input' => $request,
        ]);
    }

    public function postLogin(): RedirectResponse
    {
        $request = $this->getFormRequest();
        $remember = (bool) $request->get('remember', false);

        return $this->attemptLogin($request->validated(), $remember);
    }

    public function getConfirm(Request $request): View
    {
        return view('arbory::controllers.security.confirm', [
            'input' => $request,
        ]);
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
        return app(get_class($this->security->getFormRequest()));
    }
}
