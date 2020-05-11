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
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * ArboryAdminHasAccessMiddleware constructor.
     * @param Sentinel $sentinel
     */
    public function __construct(Sentinel $sentinel)
    {
        $this->sentinel = $sentinel;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param string $permission
     * @return JsonResponse|RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
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

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function denied(Request $request)
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
