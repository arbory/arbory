<?php

namespace CubeSystems\Leaf\CRUD;

use CubeSystems\Leaf\CRUD\Routing\Route;
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
