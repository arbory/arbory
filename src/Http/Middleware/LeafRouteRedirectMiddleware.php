<?php

namespace CubeSystems\Leaf\Http\Middleware;

use Closure;
use CubeSystems\Leaf\Pages\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeafRouteRedirectMiddleware
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
        $url = $request->url();
        $redirect = Redirect::query()
            ->where( 'from_url', $url )
            ->orWhere( 'from_url', $url . '/' )
            ->first( [ 'to_url' ] );

        if( $redirect )
        {
            return \Redirect::to( $redirect->to_url, 301 );
        }

        return $next( $request );
    }
}