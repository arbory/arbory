<?php

namespace CubeSystems\Leaf\Admin\Module;

use CubeSystems\Leaf\Services\Module;
use Illuminate\Routing\Controller;

/**
 * Class ResourceRoutes
 * @package CubeSystems\Leaf\Admin\Module
 */
class ResourceRoutes
{
    /**
     * @var string
     */
    protected $module;

    /**
     * ResourceRoutes constructor.
     * @param Module $module
     */
    public function __construct( Module $module )
    {
        $this->module = $module;
    }

    /**
     * @param $name
     * @param array $parameters
     * @return string
     */
    public function getUrl( $name, $parameters = [] )
    {
        return route( config( 'leaf.uri' ) . '.' . $this->module->name() . '.' . $name, $parameters );
    }
}
