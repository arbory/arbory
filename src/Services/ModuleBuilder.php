<?php

namespace CubeSystems\Leaf\Services;

use CubeSystems\Leaf\Admin\Module\Route;

class ModuleBuilder
{
    /**
     * @var ModuleRegistry
     */
    protected $moduleRegistry;

    /**
     * @var Module
     */
    protected $module;

    /**
     * @param ModuleRegistry $moduleRegistry
     */
    public function __construct( ModuleRegistry $moduleRegistry )
    {
        $this->moduleRegistry = $moduleRegistry;
    }

    /**
     * @param string $controllerClass
     * @return self
     */
    public function register( string $controllerClass )
    {
        $this->module = ModuleFactory::build( $controllerClass );

        $this->moduleRegistry->register( $this->module );

        Route::register( $this->module->getControllerClass() );

        return $this;
    }

    /**
     * @param callable $routeCallback
     * @return self
     */
    public function routes( callable $routeCallback )
    {
        Route::register( $this->module->getControllerClass(), $routeCallback );

        return $this;
    }

    /**
     * @param array|string $roles
     * @return self
     */
    public function roles( $roles )
    {
        $configuration = $this->module->getConfiguration();

        if( is_string( $roles ) )
        {
            $roles = [ $roles ];
        }

        $configuration->setAuthorizationType( Module::AUTHORIZATION_TYPE_ROLES );
        $configuration->setAuthorizedRoles( $roles );

        return $this;
    }

    /**
     * @param array|string $permissions
     * @return self
     */
    public function permissions( $permissions )
    {
        $configuration = $this->module->getConfiguration();

        if( is_string( $permissions ) )
        {
            $permissions = [ $permissions ];
        }

        $configuration->setAuthorizationType( Module::AUTHORIZATION_TYPE_PERMISSIONS );
        $configuration->setRequiredPermissions( $permissions );

        return $this;
    }

    /**
     * @return Module
     */
    public function getModule(): Module
    {
        return $this->module;
    }
}