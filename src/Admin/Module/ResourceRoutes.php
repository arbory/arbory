<?php

namespace CubeSystems\Leaf\Admin\Module;

use Illuminate\Routing\Controller;

class ResourceRoutes
{
    public function __construct( Controller $controller )
    {
        $this->baseName = Route::getControllerSlug( get_class( $controller ) );
    }

    public function getUrl( $name, $parameters = [] )
    {
        return route( config( 'leaf.uri' ) . '.' . $this->baseName . '.' . $name, $parameters );
    }
}
