<?php

namespace Arbory\Base\Admin\Module;

use Closure;
use Arbory\Base\Admin\Module;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;

/**
 * Class ModuleRoutesRegistry.
 */
class ModuleRoutesRegistry
{
    /**
     * @var ResourceRoutes[]
     */
    protected $routes = [];

    /**
     * @param \Arbory\Base\Admin\Module $module
     * @param Closure|null $callback
     * @return ResourceRoutes
     */
    public function register(Module $module, Closure $callback = null)
    {
        $class = $module->getControllerClass();
        $slug = $module->name();

        /**
         * @var Router
         */
        $router = app('router');

        if ($callback !== null) {
            $attributes = [
                'as' => $slug.'.',
                'prefix' => $slug,
            ];

            $router->group($attributes, $callback);
        }

        $router->resource($slug, '\\'.$class);

        $router->get($slug.'/{resourceId}/inline-edit', [
            'as' => $slug.'.inline.edit',
            'uses' => '\\'.$class.'@inlineEdit',
        ]);
        $router->put($slug.'/{resourceId}/inline-update', [
            'as' => $slug.'.inline.update',
            'uses' => '\\'.$class.'@inlineUpdate',
        ]);

        $router->post($slug.'/bulkupdate', [
            'as' => $slug.'.bulkupdate',
            'uses' => '\\'.$class.'@bulkUpdate',
        ]);

        $router->post($slug.'/filter', [
            'as' => $slug.'.filter.store',
            'uses' => '\\'.$class.'@storeFilter',
        ]);
        $router->delete($slug.'/filter/{filterId}', [
            'as' => $slug.'.filter.destroy',
            'uses' => '\\'.$class.'@destroyFilter',
        ]);

        $router->get($slug.'/dialog/{dialog}', [
            'as' => $slug.'.dialog',
            'uses' => '\\'.$class.'@dialog',
        ]);

        $router->any($slug.'/api/{api}', [
            'as' => $slug.'.api',
            'uses' => '\\'.$class.'@api',
        ]);

        $router->get($slug.'/export/{as}', [
            'as' => $slug.'.export',
            'uses' => '\\'.$class.'@export',
        ]);

        $this->routes[$module->name()] = new ResourceRoutes($module);

        return $this->routes[$module->name()];
    }

    /**
     * @param Module $module
     * @return ResourceRoutes
     */
    public function findByModule(Module $module)
    {
        return Arr::get($this->routes, $module->name());
    }
}
