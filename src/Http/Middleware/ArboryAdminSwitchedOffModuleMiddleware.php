<?php

namespace Arbory\Base\Http\Middleware;

use Arbory\Base\Admin\Module;
use Arbory\Base\Support\Facades\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class ArboryAdminSwitchOffModuleMiddleware.
 */
class ArboryAdminSwitchedOffModuleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $availableModule = $this->getFirstAvailableModule($request);

        if (! $availableModule) {
            throw new AccessDeniedHttpException();
        }

        return redirect($availableModule->url('index'));
    }

    protected function getFirstAvailableModule(Request $request): ?Module
    {
        $switchedOffModule = $this->resolveSwitchedOffModule($request);

        if (! $switchedOffModule) {
            return null;
        }

        return Admin::modules()->first(fn(Module $module) => $module->isAuthorized()
            && $module->getControllerClass() !== $switchedOffModule->getControllerClass());
    }

    private function resolveSwitchedOffModule(Request $request): ?Module
    {
        $controller = $request->route()->getController();

        return \Admin::modules()->findModuleByController($controller);
    }
}
