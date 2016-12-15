<?php

namespace CubeSystems\Leaf\Http\Middleware;

use Cartalyst\Sentinel\Sentinel;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class SentinelUserHasAccess
 * @package CubeSystems\Leaf\Http\Middleware
 */
class LeafAdminHasAccessMiddleware
{
    /**
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * LeafAdminHasAccessMiddleware constructor.
     * @param Sentinel $sentinel
     */
    public function __construct( Sentinel $sentinel )
    {
        $this->sentinel = $sentinel;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param string $permission
     * @return JsonResponse|RedirectResponse
     */
    public function handle( Request $request, Closure $next, $permission )
    {
        if( !$this->sentinel->check() )
        {
            return $this->denied( $request );
        }

        /** @noinspection PhpUndefinedMethodInspection */
        if( !$this->sentinel->hasAccess( $permission ) )
        {
            return $this->denied( $request );
        }

        return $next( $request );
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function denied( Request $request )
    {
        if( $request->ajax() )
        {
            $message = trans( 'leaf.admin_unauthorized', 'Unauthorized' );

            return response()->json( [ 'error' => $message ], 401 );
        }
        else
        {
            $message = trans( 'leaf.admin_need_permission', 'You do not have permission to do that.' );
            session()->flash( 'error', $message );

            return redirect()->back();
        }
    }
}
