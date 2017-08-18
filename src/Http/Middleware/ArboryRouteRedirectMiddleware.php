<?php

namespace Arbory\Base\Http\Middleware;

use Closure;
use Arbory\Base\Pages\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ArboryRouteRedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return RedirectResponse
     */
    public function handle( $request, Closure $next )
    {
        $redirect = Redirect::query();

        $redirect->where( 'from_url', $request->url() );
        $redirect->orWhere( 'from_url', 'LIKE', '%' . $request->path() . '%' );

        $redirect = $redirect->first( [ 'to_url' ] );

        if( $redirect )
        {
            return \Redirect::to( $redirect->to_url, 301 );
        }

        return $next( $request );
    }
}
