<?php

namespace Arbory\Base\Providers;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Arbory\Base\Http\Middleware\ArboryRouteRedirectMiddleware;

class RedirectsServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $router->aliasMiddleware('arbory.route_redirect', ArboryRouteRedirectMiddleware::class);

        $this->app->booted(function ($app) {
            $app[Kernel::class]->prependMiddleware(ArboryRouteRedirectMiddleware::class);
        });
    }
}
