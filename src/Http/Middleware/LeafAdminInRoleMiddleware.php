<?php

namespace CubeSystems\Leaf\Http\Middleware;

use Cartalyst\Sentinel\Roles\RoleInterface;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Sentinel;

/**
 * Class SentinelUserInRole
 * @package CubeSystems\Leaf\Http\Middleware
 */
class LeafAdminInRoleMiddleware
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
     * @param string|int|RoleInterface $role
     * @return JsonResponse|RedirectResponse|mixed
     */
    public function handle( Request $request, Closure $next, $role )
    {
        if( !$this->sentinel->check() )
        {
            return $this->denied( $request );
        }

        /** @noinspection PhpUndefinedMethodInspection */
        if( !$this->sentinel->inRole( $role ) )
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
