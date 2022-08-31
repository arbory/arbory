<?php

namespace Arbory\Base\Http\Middleware;

use Closure;
use Sentinel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Cartalyst\Sentinel\Roles\RoleInterface;

/**
 * Class SentinelUserInRole.
 */
class ArboryAdminInRoleMiddleware
{
    /**
     * ArboryAdminInRoleMiddleware constructor.
     */
    public function __construct(protected Sentinel $sentinel)
    {
    }

    /**
     * @return JsonResponse|RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next, string|int|\Cartalyst\Sentinel\Roles\RoleInterface $role)
    {
        if (! $this->sentinel->check()) {
            return $this->denied($request);
        }

        /* @noinspection PhpUndefinedMethodInspection */
        if (! $this->sentinel->inRole($role)) {
            return $this->denied($request);
        }

        return $next($request);
    }

    public function denied(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if ($request->ajax()) {
            $message = trans('arbory.admin_unauthorized', 'Unauthorized');

            return response()->json(['error' => $message], 401);
        } else {
            $message = trans('arbory.admin_need_permission', 'You do not have permission to do that.');
            session()->flash('error', $message);

            return redirect()->back();
        }
    }
}
