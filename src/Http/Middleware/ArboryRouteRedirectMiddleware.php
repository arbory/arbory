<?php

namespace Arbory\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Arbory\Base\Pages\Redirect;
use Illuminate\Http\RedirectResponse;

class ArboryRouteRedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $redirect = Redirect::query();

        $redirect->whereIn('from_url', [$request->url(), $request->path()]);
        $redirect->orWhere('from_url', 'LIKE', '_'.$request->path().'_');

        $redirect = $redirect->first(['to_url', 'status']);

        if ($redirect) {
            return redirect($redirect->to_url, $redirect->status);
        }

        return $next($request);
    }
}
