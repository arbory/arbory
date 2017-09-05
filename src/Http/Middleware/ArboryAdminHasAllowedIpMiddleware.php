<?php

namespace Arbory\Base\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ArboryAdminHasAllowedIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return RedirectResponse|null
     */
    public function handle( $request, Closure $next )
    {
        if( $this->isAllowedIp( $request ) )
        {
            return $next( $request );
        }

        return abort( 403 );
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function isAllowedIp( Request $request ): bool
    {
        $ips = $this->getAllowedIps();

        return empty( $ips ) || in_array( $request->ip(), $ips, true );
    }

    /**
     * @return array
     */
    protected function getAllowedIps()
    {
        return config( 'arbory.auth.ip.allowed', [] );
    }
}