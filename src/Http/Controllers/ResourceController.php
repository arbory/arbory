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
     * @param  string $slug
     * @return Response
     * @throws HttpException
     */
    public function index( $slug )
    {
        $controller = $this->findControllerBySlug( $slug );

        return $this->app->call( "{$controller}@index" );
    }

    /**
     * @param  string $slug
     * @return Response
     * @throws HttpException
     */
    public function create( $slug )
    {
        $controller = $this->findControllerBySlug( $slug );

        return $this->app->call( "{$controller}@create" );
    }

    /**
     * @param  string $slug
     * @return Response
     * @throws HttpException
     */
    public function store( $slug )
    {
        $controller = $this->findControllerBySlug( $slug );

        return $this->app->call( "{$controller}@store" );
    }

    /**
     * @param  string $slug
     * @param  int $resourceId
     * @return Response
     * @throws HttpException
     */
    public function edit( $slug, $resourceId )
    {
        $controller = $this->findControllerBySlug( $slug );

        return $this->app->call( "{$controller}@edit", [ $resourceId ] );
    }

    /**
     * @param  string $slug
     * @param  int $resourceId
     * @return Response
     * @throws HttpException
     */
    public function update( $slug, $resourceId )
    {
        $controller = $this->findControllerBySlug( $slug );

        return $this->app->call( "{$controller}@update", [ $resourceId ] );
    }

    /**
     * @param $slug
     * @param $resourceId
     * @return Response
     * @throws HttpException
     */
    public function confirmDestroy( $slug, $resourceId )
    {
        $controller = $this->findControllerBySlug( $slug );

        return $this->app->call( "{$controller}@confirmDestroy", [ $resourceId ] );
    }

    /**
     * @param $slug
     * @param $resourceId
     * @return Response
     * @throws HttpException
     */
    public function destroy( $slug, $resourceId )
    {
        $controller = $this->findControllerBySlug( $slug );

        return $this->app->call( "{$controller}@destroy", [ $resourceId ] );
    }

    /**
     * @param $slug
     * @param $resourceId
     * @param $action
     * @return Response
     * @throws HttpException
     */
    public function handleGetAction( $slug, $resourceId, $action )
    {
        $controller = $this->findControllerBySlug( $slug );

        return $this->app->call( "{$controller}@handleGetAction", [ $resourceId, $action ] );
    }

    /**
     * @param $slug
     * @return string
     * @throws HttpException
     */
    protected function findControllerBySlug( $slug )
    {
        /**
         * @var $menuItem Item
         */

        $menuItem = $this->app['leaf.menu']->findItemBySlug( $slug );

        if( !$menuItem )
        {
            $this->app->abort( Response::HTTP_NOT_FOUND );
        }

        return $menuItem->getController();
    }
}
