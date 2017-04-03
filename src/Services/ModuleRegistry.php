<?php

namespace CubeSystems\Leaf\Services;

/**
 * Class ModuleRegistryService
 * @package CubeSystems\Leaf\Services
 */
class ModuleRegistry
{
    /**
     * @var Module[]
     */
    protected $modulesByName;

    /**
     * @var Module[]
     */
    protected $modulesByControllerClass;

    /**
     * ModuleRegistry constructor.
     * @param array $modulesConfigArray
     */
    public function __construct( array $modulesConfigArray = [] )
    {
        foreach( $modulesConfigArray as $moduleConfigArray )
        {
            $moduleConfig = new ModuleConfiguration( $moduleConfigArray );

            $this->register( new Module( $moduleConfig ) );
        }
    }

    /**
     * @param Module $module
     */
    public function register( Module $module )
    {
        if( isset( $this->modulesByName[$module->getName()] ) )
        {
            throw new \LogicException( 'Module named "' . $module->getName() . '" already registered' );
        }

        if( isset( $this->modulesByName[$module->getControllerClass()] ) )
        {
            throw new \LogicException( 'Module with controller class "' . $module->getControllerClass() . '" already registered' );
        }

        $this->modulesByName[$module->getName()] = $module;
        $this->modulesByControllerClass[ltrim( $module->getControllerClass(), '\\' )] = $module;
    }

    /**
     * @param string $moduleName
     * @return Module|null
     */
    public function findModuleByName( $moduleName )
    {
        return isset( $this->modulesByName[$moduleName] )
            ? $this->modulesByName[$moduleName]
            : null;
    }

    /**
     * @param string $controllerClass
     * @return Module
     */
    public function findModuleByControllerClass( $controllerClass )
    {
        return isset( $this->modulesByControllerClass[$controllerClass] )
            ? $this->modulesByControllerClass[$controllerClass]
            : null;
    }

    /**
     * @param $instance
     * @return Module
     */
    public function findModuleByController( $instance )
    {
        return $this->findModuleByControllerClass( get_class( $instance ) );
    }
}
