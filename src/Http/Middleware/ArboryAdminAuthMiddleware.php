<?php

namespace Arbory\Base\Http\Middleware;

use Cartalyst\Sentinel\Sentinel;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!$this->sentinel->check()) {
            return $this->denied($request);
        }

        return $next($request);
    }

    private function denied(Request $request): RedirectResponse|JsonResponse
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
