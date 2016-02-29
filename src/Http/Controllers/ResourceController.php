<?php

namespace CubeSystems\Leaf\Http\Controllers;

use CubeSystems\Leaf\Menu\Item;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;


/**
 * Class ResourceController
 * @package CubeSystems\Leaf\Http\Controllers
 */
class ResourceController extends Controller
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * ResourceController constructor.
     * @param Application $app
     */
    public function __construct( Application $app )
    {
        $this->app = $app;
    }

    /**
     * @param  string $name
     * @return Response
     * @throws HttpException
     */
    public function index( $name )
    {
        $controller = $this->getClassFromSlug( $name );

        return $this->app->call( "{$controller}@index" );
    }

    /**
     * @param  string $name
     * @return Response
     * @throws HttpException
     */
    public function create( $name )
    {
        $controller = $this->getClassFromSlug( $name );

        return $this->app->call( "{$controller}@create" );
    }

    /**
     * @param  string $name
     * @return Response
     * @throws HttpException
     */
    public function store( $name )
    {
        $controller = $this->getClassFromSlug( $name );

        return $this->app->call( "{$controller}@store" );
    }

    /**
     * @param  string $name
     * @param  int $id
     * @return Response
     * @throws HttpException
     */
    public function edit( $name, $id )
    {
        $controller = $this->getClassFromSlug( $name );

        return $this->app->call( "{$controller}@edit", [ $id ] );
    }

    /**
     * @param  string $name
     * @param  int $id
     * @return Response
     * @throws HttpException
     */
    public function update( $name, $id )
    {
        $controller = $this->getClassFromSlug( $name );

        return $this->app->call( "{$controller}@update", [ $id ] );
    }

    /**
     * @param $name
     * @param $id
     * @return Response
     * @throws HttpException
     */
    public function confirmDestroy( $name, $id )
    {
        $controller = $this->getClassFromSlug( $name );

        return $this->app->call( "{$controller}@confirmDestroy", [ $id ] );
    }

    /**
     * @param $name
     * @param $id
     * @return Response
     * @throws HttpException
     */
    public function destroy( $name, $id )
    {
        $controller = $this->getClassFromSlug( $name );

        return $this->app->call( "{$controller}@destroy", [ $id ] );
    }

    /**
     * @param $name
     * @param $id
     * @param $action
     * @return Response
     * @throws HttpException
     */
    public function handleGetAction( $name, $id, $action )
    {
        $controller = $this->getClassFromSlug( $name );

        return $this->app->call( "{$controller}@handleGetAction", [ $id, $action ] );
    }

    /**
     * @param $name
     * @param $id
     * @param $action
     * @return Response
     * @throws HttpException
     */
    public function handlePostAction( $name, $id, $action )
    {
        $controller = $this->getClassFromSlug( $name );

        return $this->app->call( "{$controller}@handlePostAction", [ $id, $action ] );
    }

    /**
     * @param $slug
     * @return string
     * @throws HttpException
     */
    public function getClassFromSlug( $slug )
    {
        /**
         * @var $menuItem Item
         */

        $menuItem = $this->app['leaf.menu']->findItemBySlug( $slug );

        if( !$menuItem )
        {
            $this->app->abort( \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND );
        }

        return $menuItem->getController();
    }
}
