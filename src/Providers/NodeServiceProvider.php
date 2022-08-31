<?php

namespace Arbory\Base\Providers;

use Arbory\Base\Nodes\Mixins\Collection as NodesCollectionMixin;
use Arbory\Base\Nodes\Node;
use Arbory\Base\Support\Facades\Page;
use Arbory\Base\Support\Facades\Admin;
use File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Arbory\Base\Support\Facades\Settings;
use Arbory\Base\Nodes\ContentTypeRegister;
use Illuminate\Routing\Events\RouteMatched;
use Arbory\Base\Repositories\NodesRepository;
use Arbory\Base\Services\Content\PageBuilder;
use Arbory\Base\Support\Facades\ArboryRouter;
use Illuminate\Routing\Router as LaravelRouter;
use Arbory\Base\Nodes\ContentTypeRoutesRegister;
use Schema;

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

        $this->app->singleton(ContentTypeRegister::class, fn() => new ContentTypeRegister());

        $this->app->singleton('arbory_router', fn() => $this->app->make(ContentTypeRoutesRegister::class));

        $this->app->singleton('arbory_page_builder', fn() => new PageBuilder(
            $this->app->make(ContentTypeRegister::class),
            $this->app->make('arbory_router')
        ));

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
            $this->app->singleton(Node::class, fn() => $this->routes->getCurrentNode());
        });

        if (! $this->app->routesAreCached()) {
            $this->routes->registerNodes();
        }
    }

    protected function isDbConfigured(): bool
    {
        try {
            \DB::connection()->getPdo();
        } catch (\Exception) {
            return false;
        }

        return Schema::hasTable('nodes');
    }

    /**
     * @return void
     */
    protected function purgeOutdatedRouteCache()
    {
        if ($this->app->routesAreCached()) {
            $path = $this->app->getCachedRoutesPath();

            if ($this->canReadSettings() && $this->isRouteCacheOutdated($path)) {
                File::delete($path);
            }
        }
    }

    /**
     * @return bool
     */
    protected function isRouteCacheOutdated(string $path)
    {
        $modified = File::lastModified($path);

        return $modified < $this->getNodeModificationTimestamp();
    }

    /**
     * @return mixed
     */
    protected function getNodeModificationTimestamp()
    {
        $repository = new NodesRepository;

        return $repository->getLastUpdateTimestamp();
    }

    protected function canReadSettings(): bool
    {
        return Schema::hasTable('settings');
    }

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
