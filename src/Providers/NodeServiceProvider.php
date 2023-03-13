<?php

namespace Arbory\Base\Providers;

use Arbory\Base\Nodes\ContentTypeRegister;
use Arbory\Base\Nodes\ContentTypeRoutesRegister;
use Arbory\Base\Nodes\Mixins\Collection as NodesCollectionMixin;
use Arbory\Base\Nodes\Node;
use Arbory\Base\Repositories\NodesRepository;
use Arbory\Base\Services\Content\PageBuilder;
use Arbory\Base\Services\NodeRoutesCache;
use Arbory\Base\Support\Facades\Admin;
use Arbory\Base\Support\Facades\ArboryRouter;
use Arbory\Base\Support\Facades\Page;
use Arbory\Base\Support\Facades\Settings;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router as LaravelRouter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

/**
 * Class NodeServiceProvider.
 */
class NodeServiceProvider extends ServiceProvider
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var ContentTypeRoutesRegister
     */
    protected $routes;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        AliasLoader::getInstance()->alias('ArboryRouter', ArboryRouter::class);
        AliasLoader::getInstance()->alias('Admin', Admin::class);
        AliasLoader::getInstance()->alias('Page', Page::class);
        AliasLoader::getInstance()->alias('Settings', Settings::class);

        $this->app->singleton(NodesRepository::class, function () {
            $repository = new NodesRepository();

            $repository->setQueryOnlyActiveNodes(true);

            return $repository;
        });

        $this->app->singleton(ContentTypeRegister::class, function () {
            return new ContentTypeRegister();
        });

        $this->app->singleton('arbory_router', function () {
            return $this->app->make(ContentTypeRoutesRegister::class);
        });

        $this->app->singleton('arbory_page_builder', function () {
            return new PageBuilder(
                $this->app->make(ContentTypeRegister::class),
                $this->app->make('arbory_router')
            );
        });

        Collection::mixin(new NodesCollectionMixin);

        $this->routes = $this->app->make('arbory_router');
    }

    /**
     * @return void
     */
    public function boot()
    {
        $this->registerContentTypes();
        $this->registerNodes();
        $this->detectCurrentLocaleFromRoute($this->app['router']);
        $this->purgeOutdatedRouteCache();
        $this->refreshObsoleteRouteCache();
    }

    /**
     * @return void
     */
    protected function registerContentTypes()
    {
        $path = base_path('routes/pages.php');

        if (! File::exists($path)) {
            return;
        }

        $this->app['router']->group([
            'middleware' => 'web',
            'namespace' => 'App\Http\Controllers',
        ], function () use ($path) {
            include $path;
        });
    }

    /**
     * @return void
     */
    protected function registerNodes()
    {
        if (app()->runningInConsole()) {
            if ($this->isDbConfigured() && ! $this->app->routesAreCached()) {
                $this->routes->registerNodes();
            }

            return;
        }

        $this->app->booted(function () {
            $this->app->singleton(Node::class, function () {
                return $this->routes->getCurrentNode();
            });
        });

        if (! $this->app->routesAreCached()) {
            $this->routes->registerNodes();
        }
    }

    /**
     * @return bool
     */
    protected function isDbConfigured(): bool
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return false;
        }

        return Schema::hasTable('nodes');
    }

    /**
     * @return void
     */
    protected function purgeOutdatedRouteCache()
    {
        if (!config('arbory.clear_obsolete_route_cache')) {
            return;
        }

        if ($this->app->routesAreCached() && $this->canReadSettings() && NodeRoutesCache::isRouteCacheObsolete()) {
            NodeRoutesCache::clearCache();
        }
    }

    protected function refreshObsoleteRouteCache(): void
    {
        if (!config('arbory.refresh_route_cache')) {
            return;
        }

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->call(fn() => Artisan::call('arbory:route-cache'))->everyMinute();
        });
    }

    /**
     * @return bool
     */
    protected function canReadSettings(): bool
    {
        return Schema::hasTable('settings');
    }

    /**
     * @param  LaravelRouter  $router
     */
    protected function detectCurrentLocaleFromRoute(LaravelRouter $router)
    {
        $router->matched(function (RouteMatched $event) {
            $locales = config('translatable.locales');
            $firstSegment = $event->request->segment(1);

            if (in_array($firstSegment, $locales, true)) {
                $this->app->setLocale($firstSegment);
                $this->app['request']->setLocale($firstSegment);
            }
        });
    }
}
