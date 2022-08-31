<?php

namespace Arbory\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Sentinel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

/**
 * Class ArboryAdminHasAccessMiddleware.
 */
class ArboryAdminHasAccessMiddleware
{
    /**
     * ArboryAdminHasAccessMiddleware constructor.
     */
    public function __construct(protected Sentinel $sentinel)
    {
    }

    /**
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, $permission): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if (! $this->sentinel->check()) {
            return $this->denied($request);
        }

        /* @noinspection PhpUndefinedMethodInspection */
        if (! $this->sentinel->hasAccess($permission)) {
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
