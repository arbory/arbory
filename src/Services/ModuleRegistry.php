<?php

namespace Arbory\Base\Services;

use Closure;
use LogicException;
use Arbory\Base\Admin\Admin;
use Arbory\Base\Admin\Module;
use Arbory\Base\Services\Permissions\ModulePermissionsRegistry;
use Illuminate\Support\Collection;

/**
 * Class ModuleRegistryService.
 */
class ModuleRegistry
{
    /**
     * @var Collection
     */
    protected $modules;

    /**
     * ModuleRegistry constructor.
     */
    public function __construct(protected Admin $admin)
    {
        $this->modules = new Collection();
    }

    /**
     * @param Closure|null $routes
     * @return Module
     */
    public function register(string $controllerClass, Closure $routes = null)
    {
        if ($this->modules->has($controllerClass)) {
            throw new LogicException('Module with controller class "'.$controllerClass.'" already registered');
        }

        $config = new ModuleConfiguration($controllerClass);
        $permissions = new ModulePermissionsRegistry($controllerClass);
        $module = new Module($this->admin, $config, $permissions);

        $this->admin->routes()->register($module, $routes);

        $this->modules->put($controllerClass, $module);

        return $module;
    }

    /**
     * @param  string  $controllerClass
     * @return Module
     */
    public function findModuleByControllerClass($controllerClass)
    {
        return $this->modules->get($controllerClass);
    }

    /**
     * @param $instance
     * @return Module
     */
    public function findModuleByController($instance)
    {
        return $this->findModuleByControllerClass($instance::class);
    }

    /**
     * @return Collection|Module[]
     */
    public function all(): Collection
    {
        return $this->modules;
    }

    /**
     * @param $method
     * @param $parameters
     * @return Collection|Module[]|Module
     */
    public function __call($method, $parameters)
    {
        return $this->modules->$method(...$parameters);
    }
}
