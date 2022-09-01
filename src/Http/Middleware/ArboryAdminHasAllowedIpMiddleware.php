<?php

namespace Arbory\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArboryAdminHasAllowedIpMiddleware
{

    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->isAllowedIp($request)) {
            return $next($request);
        }

        return abort(403);
    }

    protected function isAllowedIp(Request $request): bool
    {
        $ips = $this->getAllowedIps();
        $requestIp = $request->ip();

        if (empty($ips)) {
            return true;
        }

        foreach ($ips as $allowed) {
            if (Str::contains($allowed, '-')) {
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

    protected function getAllowedIps(): array
    {
        return config('arbory.auth.ip.allowed', []);
    }
}
