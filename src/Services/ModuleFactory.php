<?php

namespace CubeSystems\Leaf\Services;

class ModuleFactory
{
    /**
     * @param string $controllerClass
     * @return Module
     */
    public static function build( string $controllerClass )
    {
        $config = new ModuleConfiguration();

        $config->setName( $controllerClass );
        $config->setControllerClass( $controllerClass );

        return new Module( $config );
    }
}