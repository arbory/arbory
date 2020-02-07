<?php

namespace Arbory\Base\Http\Middleware;

use Arbory\Base\Admin\Module;
use Arbory\Base\Support\Facades\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class ArboryAdminSwitchOffModuleMiddleware
 */
class ArboryAdminSwitchedOffModuleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
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

    /**
     * @param Request $request
     * @return Module|null
     */
    protected function getFirstAvailableModule(Request $request): ?Module
    {
        $switchedOffModule = $this->resolveSwitchedOffModule($request);

        if (! $switchedOffModule) {
            return null;
        }

        return Admin::modules()->first(function (Module $module) use ($switchedOffModule) {
            return $module->isAuthorized() && $module->getControllerClass() !== $switchedOffModule->getControllerClass();
        });
    }

    /**
     * @param Request $request
     * @return Module|null
     */
    private function resolveSwitchedOffModule(Request $request): ?Module
    {
        $controller = $request->route()->getController();

        return \Admin::modules()->findModuleByController($controller);
    }
}
