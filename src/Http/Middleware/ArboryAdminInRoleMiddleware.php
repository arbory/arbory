<?php

namespace Arbory\Base\Http\Middleware;

use Cartalyst\Sentinel\Roles\RoleInterface;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Sentinel;

/**
 * Class SentinelUserInRole
 * @package Arbory\Base\Http\Middleware
 */
class ArboryAdminInRoleMiddleware
{
    /**
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * ArboryAdminInRoleMiddleware constructor.
     * @param Sentinel $sentinel
     */
    public function __construct(Sentinel $sentinel)
    {
        $this->sentinel = $sentinel;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param string|int|RoleInterface $role
     * @return JsonResponse|RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$this->sentinel->check()) {
            return $this->denied($request);
        }

        /** @noinspection PhpUndefinedMethodInspection */
        if (!$this->sentinel->inRole($role)) {
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
