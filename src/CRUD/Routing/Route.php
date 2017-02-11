<?php

namespace CubeSystems\Leaf\CRUD\Routing;

use Closure;
use Illuminate\Routing\Router;

class Route
{
    /**
     * @var array|string[]
     */
    protected static $controllerSlugs = [];

    public static function register( $class, Closure $callback = null )
    {
        $slug = static::generateSlugFromClassName( $class );

        /**
         * @var $router Router
         */
        $router = app( 'router' );
        $router->resource( $slug, '\\' . $class, [ 'except' => 'show' ] );

        $name = $router->getLastGroupPrefix() . '.' . $slug;

        $router->get( $slug . '/dialog/{dialog}', [
            'as' => $name . '.dialog',
            'uses' => '\\' . $class . '@dialog'
        ] );

        if( $callback !== null )
        {
            $attributes = [
                'as' => $name,
                'prefix' => $slug,
            ];

            $router->group( $attributes, $callback );
        }

        static::$controllerSlugs[$class] = $slug;
    }

    /**
     * @param $class
     * @return string
     */
    protected static function generateSlugFromClassName( $class )
    {
        if( !preg_match( '#Controllers(\\\Admin)?\\\(?P<name>.*)Controller#ui', $class, $matches ) )
        {
            return substr( md5( $class ), 0, 8 );
        }

        $slug = str_replace( '\\', '', $matches['name'] );
        $slug = preg_replace( '/([a-zA-Z])(?=[A-Z])/', '$1 ', $slug );

        return str_slug( $slug );
    }

    /**
     * @param $class
     * @return string
     */
    public static function getControllerSlug( $class )
    {
        return array_get( static::$controllerSlugs, $class );
    }
}
