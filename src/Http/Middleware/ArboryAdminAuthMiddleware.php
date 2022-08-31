<?php

namespace Arbory\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Sentinel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

/**
 * Class ArboryAdminAuthMiddleware.
 */
class ArboryAdminAuthMiddleware
{
    /**
     * ArboryAdminAuthMiddleware constructor.
     */
    public function __construct(protected Sentinel $sentinel)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $this->sentinel->check()) {
            return $this->denied($request);
        }

        return $next($request);
    }

    private function denied(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
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
