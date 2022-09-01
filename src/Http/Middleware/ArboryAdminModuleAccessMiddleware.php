<?php

namespace Arbory\Base\Http\Middleware;

use Arbory\Base\Admin\Module;
use Arbory\Base\Support\Facades\Admin;
use Cartalyst\Sentinel\Sentinel;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

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
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $targetModule = $this->resolveTargetModule($request);

        if (!$targetModule) {
            throw new RuntimeException('Could not find target module for route controller');
        }

        if (!$targetModule->isRequestAuthorized($request)) {
            return $this->denied($request);
        }

        return $next($request);
    }

    private function denied(Request $request): RedirectResponse|JsonResponse
    {
        $message = 'Unauthorized';

        if ($request->ajax()) {
            return response()->json(['error' => $message], 401);
        }

        return redirect()
            ->back()
            ->withErrors($message);
    }

    private function resolveTargetModule(Request $request): ?Module
    {
        $controller = $request->route()->getController();

        return Admin::modules()->findModuleByController($controller);
    }
}
