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
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * ArboryAdminModuleAccessMiddleware constructor.
     * @param Sentinel $sentinel
     */
    public function __construct(Sentinel $sentinel)
    {
        $this->sentinel = $sentinel;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $targetModule = $this->resolveTargetModule($request);

        if (! $targetModule) {
            throw new \RuntimeException('Could not find target module for route controller');
        }

        if (! $targetModule->isAuthorized()) {
            return $this->denied($request);
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    private function denied(Request $request)
    {
        $message = 'Unauthorized';

        if ($request->ajax()) {
            return response()->json(['error' => $message], 401);
        }

        return redirect()
            ->guest(route('admin.login.form'))
            ->with('error', $message);
    }

    /**
     * @param Request $request
     * @return \Arbory\Base\Admin\Module|null
     */
    private function resolveTargetModule(Request $request)
    {
        $controller = $request->route()->getController();

        return \Admin::modules()->findModuleByController($controller);
    }
}
