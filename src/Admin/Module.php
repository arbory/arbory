<?php

namespace Arbory\Base\Admin;

use Arbory\Base\Admin\Widgets\Breadcrumbs;
use Arbory\Base\Admin\Module\ResourceRoutes;
use Arbory\Base\Services\ModuleConfiguration;

/**
 * Class Module.
 */
class Module
{
    const AUTHORIZATION_TYPE_ROLES = 'roles';
    const AUTHORIZATION_TYPE_PERMISSIONS = 'permissions';
    const AUTHORIZATION_TYPE_NONE = 'none';

    /**
     * @var Admin
     */
    protected $admin;

    /**
     * @var ModuleConfiguration
     */
    private $configuration;

    /**
     * @var ResourceRoutes
     */
    protected $routes;

    protected $breadcrumbs;

    /**
     * @param Admin $admin
     * @param ModuleConfiguration $configuration
     */
    public function __construct(Admin $admin, ModuleConfiguration $configuration)
    {
        $this->admin = $admin;
        $this->configuration = $configuration;
    }

    public function __toString()
    {
        return $this->name();
    }

    /**
     * @return string
     */
    public function getControllerClass()
    {
        return $this->configuration->getControllerClass();
    }

    /**
     * @return ModuleConfiguration
     */
    public function getConfiguration(): ModuleConfiguration
    {
        return $this->configuration;
    }

    /**
     * @return bool
     */
    public function isAuthorized()
    {
        return $this->admin->isAuthorizedFor($this->getControllerClass());
    }

    /**
     * @return Breadcrumbs
     */
    public function breadcrumbs()
    {
        if ($this->breadcrumbs === null) {
            $this->breadcrumbs = new Breadcrumbs();  // TODO: Move this to menu
            $this->breadcrumbs->addItem($this->name(), $this->url('index'));
        }

        return $this->breadcrumbs;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->getConfiguration()->getName();
    }

    /**
     * @param $route
     * @param array $parameters
     * @return string
     */
    public function url($route, $parameters = [])
    {
        if ($this->routes === null) {
            $this->routes = $this->admin->routes()->findByModule($this);
        }

        return $this->routes->getUrl($route, $parameters);
    }
}
