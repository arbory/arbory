<?php

namespace CubeSystems\Leaf\Http\Middleware;

use Closure;

/**
 * Class AdminMiddleware
 * @package CubeSystems\Leaf\Http\Middleware
 */
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle( $request, Closure $next )
    {
        if( $request->user()->isAdmin() !== true )
        {
            return redirect( 'home' );
        }

        return $next( $request );
    }

}
