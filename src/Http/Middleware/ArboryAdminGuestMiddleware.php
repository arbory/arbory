<?php

namespace Arbory\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Sentinel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class ArboryAdminGuestMiddleware.
 */
class ArboryAdminGuestMiddleware
{
    /**
     * ArboryAdminGuestMiddleware constructor.
     *
     * @param $sentinel
     */
    public function __construct(protected Sentinel $sentinel)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     */
    public function handle($request, Closure $next): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if ($this->sentinel->check()) {
            if ($request->ajax()) {
                $message = trans('arbory.admin_unauthorized', 'Unauthorized');

                return response()->json(['error' => $message], 401);
            } else {
                $firstAvailableModule = \Admin::modules()->first(fn($module) => $module->isAuthorized());

                if (! $firstAvailableModule) {
                    throw new AccessDeniedHttpException();
                }

                return redirect($firstAvailableModule->url('index'));
            }
        }

        return $next($request);
    }
}
