<?php

namespace Arbory\Base\Services;

use Arbory\Base\Admin\Admin;
use Arbory\Base\Admin\Module;
use Illuminate\Support\Collection;

/**
 * Class ModuleRegistryService.
 */
class ModuleRegistry
{
    /**
     * @var Admin
     */
    protected $admin;

    /**
     * @var Collection
     */
    protected $modules;

    /**
     * ModuleRegistry constructor.
     * @param Admin $admin
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
        $this->modules = new Collection();
    }

    /**
     * @param string $controllerClass
     * @param \Closure|null $routes
     * @return Module
     */
    public function register(string $controllerClass, \Closure $routes = null)
    {
        if ($this->modules->has($controllerClass)) {
            throw new \LogicException('Module with controller class "'.$controllerClass.'" already registered');
        }

        $config = new ModuleConfiguration($controllerClass);
        $module = new Module($this->admin, $config);

        $this->admin->routes()->register($module, $routes);

        $this->modules->put($controllerClass, $module);

        return $module;
    }

    /**
     * @param string $controllerClass
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
        return $this->findModuleByControllerClass(get_class($instance));
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
