<?php

namespace Arbory\Base\Providers;

use Illuminate\Routing\Router;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Arbory\Base\Http\Middleware\ArboryAdminAuthMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminGuestMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminInRoleMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminHasAccessMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminHasAllowedIpMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminModuleAccessMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminSwitchedOffModuleMiddleware;

class RoutesServiceProvider extends ServiceProvider
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Router
     */
    protected $router;

    public function boot(Filesystem $filesystem, Router $router)
    {
        $this->filesystem = $filesystem;
        $this->router = $router;

        $router->middlewareGroup('admin', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            ArboryAdminHasAllowedIpMiddleware::class,
        ]);

        $router->aliasMiddleware('arbory.admin_auth', ArboryAdminAuthMiddleware::class);
        $router->aliasMiddleware('arbory.admin_module_access', ArboryAdminModuleAccessMiddleware::class);
        $router->aliasMiddleware('arbory.admin_quest', ArboryAdminGuestMiddleware::class);
        $router->aliasMiddleware('arbory.admin_in_role', ArboryAdminInRoleMiddleware::class);
        $router->aliasMiddleware('arbory.admin_has_access', ArboryAdminHasAccessMiddleware::class);
        $router->aliasMiddleware('arbory.admin_has_allowed_ip', ArboryAdminHasAllowedIpMiddleware::class);
        $router->aliasMiddleware('arbory.admin_switched_off_module', ArboryAdminSwitchedOffModuleMiddleware::class);

        $this->registerAdminRoutes();
        $this->registerAppRoutes();
    }

    private function registerAdminRoutes()
    {
        $this->router->group([
            'as' => 'admin.',
            'middleware' => 'admin',
            'namespace' => '\Arbory\Base\Http\Controllers',
            'prefix' => config('arbory.uri'),
        ], function () {
            include __DIR__.'/../../routes/admin.php';
        });
    }

    private function registerAppRoutes()
    {
        $adminRoutes = base_path('routes/admin.php');

        if (! $this->filesystem->exists($adminRoutes)) {
            return;
        }

        $this->router->group([
            'as' => 'admin.',
            'middleware' => ['admin', 'arbory.admin_auth', 'arbory.admin_module_access'],
            'namespace' => '',
            'prefix' => config('arbory.uri'),
        ], function () use ($adminRoutes) {
            include $adminRoutes;
        });
    }
}
