<?php

namespace Arbory\Base\Http\Middleware;

use Cartalyst\Sentinel\Sentinel;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class ArboryAdminAuthMiddleware
 * @package Arbory\Base\Http\Middleware
 */
class ArboryAdminAuthMiddleware
{
    /**
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * ArboryAdminAuthMiddleware constructor.
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
        if (!$this->sentinel->check()) {
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
}
