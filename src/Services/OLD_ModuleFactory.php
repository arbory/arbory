<?php

namespace CubeSystems\Leaf\Services;

class OLDModuleFactory
{
    /**
     * @var ModuleRegistry
     */
    protected $registry;

    /**
     * ModuleFactory constructor.
     * @param ModuleRegistry $registry
     */
    public function __construct( ModuleRegistry $registry )
    {
        $this->registry = $registry;
    }

    /**
     * @param string $controllerClass
     * @return Module
     */
    public function build( string $controllerClass )
    {
        $config = new ModuleConfiguration();
        $config->setName( $controllerClass );
        $config->setControllerClass( $controllerClass );

        return new Module( $config );
    }
}
