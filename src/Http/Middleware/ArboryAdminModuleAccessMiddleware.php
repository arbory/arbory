<?php

namespace Arbory\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Sentinel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

/**
 * Class ArboryAdminModuleAccessMiddleware.
 */
class ArboryAdminModuleAccessMiddleware
{
    /**
     * ArboryAdminModuleAccessMiddleware constructor.
     */
    public function __construct(protected Sentinel $sentinel)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $targetModule = $this->resolveTargetModule($request);

        if (! $targetModule) {
            throw new \RuntimeException('Could not find target module for route controller');
        }

        if (! $targetModule->isRequestAuthorized($request)) {
            return $this->denied($request);
        }

        return $next($request);
    }

    private function denied(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $message = 'Unauthorized';

        if ($request->ajax()) {
            return response()->json(['error' => $message], 401);
        }

        return redirect()
            ->back()
            ->withErrors($message);
    }

    /**
     * @return \Arbory\Base\Admin\Module|null
     */
    private function resolveTargetModule(Request $request)
    {
        $controller = $request->route()->getController();

        return \Admin::modules()->findModuleByController($controller);
    }
}
