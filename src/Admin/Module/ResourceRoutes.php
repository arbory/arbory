<?php

namespace CubeSystems\Leaf\Admin\Module;

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
    protected $baseName;

    /**
     * ResourceRoutes constructor.
     * @param Controller $controller
     */
    public function __construct( Controller $controller )
    {
        $this->baseName = Route::getControllerSlug( get_class( $controller ) );
    }

    /**
     * @param $name
     * @param array $parameters
     * @return string
     */
    public function getUrl( $name, $parameters = [] )
    {
        return route( config( 'leaf.uri' ) . '.' . $this->baseName . '.' . $name, $parameters );
    }
}
