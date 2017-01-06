<?php

namespace CubeSystems\Leaf\Http\Middleware;

use Cartalyst\Sentinel\Sentinel;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class LeafAdminGuestMiddleware
 * @package CubeSystems\Leaf\Http\Middleware
 */
class LeafAdminGuestMiddleware
{
    /**
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * LeafAdminGuestMiddleware constructor.
     * @param $sentinel
     */
    public function __construct( Sentinel $sentinel )
    {
        $this->sentinel = $sentinel;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return JsonResponse|RedirectResponse
     */
    public function handle( $request, Closure $next )
    {
        if( $this->sentinel->check() )
        {
            if( $request->ajax() )
            {
                $message = trans( 'leaf.admin_unauthorized', 'Unauthorized' );

                return response()->json( [ 'error' => $message ], 401 );
            }
            else
            {
                return redirect( route( 'admin.dashboard' ) );
            }
        }

        return $next( $request );
    }
}
