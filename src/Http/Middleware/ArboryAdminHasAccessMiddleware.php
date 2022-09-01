<?php

namespace Arbory\Base\Http\Middleware;

use Cartalyst\Sentinel\Sentinel;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

    public function handle(Request $request, Closure $next, string $permission): mixed
    {
        if (!$this->sentinel->check()) {
            return $this->denied($request);
        }

        /* @noinspection PhpUndefinedMethodInspection */
        if (!$this->sentinel->hasAccess($permission)) {
            return $this->denied($request);
        }

        return $next($request);
    }

    public function denied(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->ajax()) {
            $message = trans('arbory.admin_unauthorized', 'Unauthorized');

            return response()->json(['error' => $message], 401);
        }

        $message = trans('arbory.admin_need_permission', 'You do not have permission to do that.');
        session()->flash('error', $message);

        return redirect()->back();
    }
}
