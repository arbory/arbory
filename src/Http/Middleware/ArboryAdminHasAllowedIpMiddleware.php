<?php

namespace Arbory\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ArboryAdminHasAllowedIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return RedirectResponse|null
     */
    public function handle($request, Closure $next)
    {
        if ($this->isAllowedIp($request)) {
            return $next($request);
        }

        return abort(403);
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function isAllowedIp(Request $request): bool
    {
        $ips = $this->getAllowedIps();
        $requestIp = $request->ip();

        if (empty($ips)) {
            return true;
        }

        foreach ($ips as $allowed) {
            if (str_contains($allowed, '-')) {
                $ip = ip2long($requestIp);
                [$from, $to] = explode('-', $allowed);

                if ($ip <= ip2long($to) && ip2long($from) <= $ip) {
                    return true;
                }
            }

            if ($requestIp === $allowed) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    protected function getAllowedIps()
    {
        return config('arbory.auth.ip.allowed', []);
    }
}
