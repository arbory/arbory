<?php

namespace Arbory\Base\Http\Middleware;

use Arbory\Base\Support\Facades\Admin;
use Cartalyst\Sentinel\Sentinel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class ArboryAdminGuestMiddleware.
 */
class ArboryAdminGuestMiddleware
{
    /**
     * ArboryAdminGuestMiddleware constructor.
     *
     * @param Sentinel $sentinel
     */
    public function __construct(protected Sentinel $sentinel)
    {
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->sentinel->check()) {
            if ($request->ajax()) {
                $message = trans('arbory.admin_unauthorized', 'Unauthorized');

                return response()->json(['error' => $message], 401);
            }

            $firstAvailableModule = Admin::modules()->first(fn($module) => $module->isAuthorized());

            if (!$firstAvailableModule) {
                throw new AccessDeniedHttpException();
            }

            return redirect($firstAvailableModule->url('index'));
        }

        return $next($request);
    }
}
