<?php

namespace CubeSystems\Leaf\Admin\Module;

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

        if( $callback !== null )
        {
            $attributes = [
                'as' => $slug . '.',
                'prefix' => $slug,
            ];

            $router->group( $attributes, $callback );
        }

        $router->resource( $slug, '\\' . $class );

        $router->get( $slug . '/dialog/{dialog}', [
            'as' => $slug . '.dialog',
            'uses' => '\\' . $class . '@dialog'
        ] );

        $router->get( $slug . '/api/{api}', [
            'as' => $slug . '.api',
            'uses' => '\\' . $class . '@api'
        ] );



        static::$controllerSlugs[$class] = $slug;
    }

    /**
     * @param $class
     * @return string
     */
    public static function generateSlugFromClassName( $class )
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
