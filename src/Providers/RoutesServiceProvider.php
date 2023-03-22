<?php

namespace Arbory\Base\Providers;

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Router;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\ServiceProvider;
use Arbory\Base\Http\Middleware\ArboryAdminAuthMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminGuestMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminInRoleMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminHasAccessMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminHasAllowedIpMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminModuleAccessMiddleware;
use Arbory\Base\Http\Middleware\ArboryAdminSwitchedOffModuleMiddleware;
use Illuminate\View\Middleware\ShareErrorsFromSession;

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

    /**
     * @param  Filesystem  $filesystem
     * @param  Router  $router
     */
    public function boot(Filesystem $filesystem, Router $router): void
    {
        $this->filesystem = $filesystem;
        $this->router = $router;

        $router->middlewareGroup('admin', [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
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

    private function registerAdminRoutes(): void
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

    private function registerAppRoutes(): void
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
