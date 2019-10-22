<?php

namespace Arbory\Base\Http\Middleware;

use Cartalyst\Sentinel\Sentinel;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class ArboryAdminGuestMiddleware
 * @package Arbory\Base\Http\Middleware
 */
class ArboryAdminGuestMiddleware
{
    /**
     * @var Sentinel
     */
    protected $sentinel;

    /**
     * ArboryAdminGuestMiddleware constructor.
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
                $message = trans( 'arbory.admin_unauthorized', 'Unauthorized' );

                return response()->json( [ 'error' => $message ], 401 );
            }
            else
            {
                $firstAvailableModule = \Admin::modules()->first( function ( $module ) { return $module->isAuthorized(); } );

                if( !$firstAvailableModule )
                {
                    throw new AccessDeniedHttpException();
                }

                return redirect( $firstAvailableModule->url('index', [], false) );
            }
        }

        return $next( $request );
    }
}
