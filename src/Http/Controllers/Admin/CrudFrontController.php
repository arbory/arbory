<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Services\ModuleRegistry;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class CrudFrontController
 * @package CubeSystems\Leaf\Http\Controllers
 */
class CrudFrontController extends Controller
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
     * @param string $resourceId
     * @return Response
     * @throws HttpException
     */
    public function destroy( $slug, $resourceId )
    {
        $controller = $this->findControllerBySlug( $slug );

        return $this->app->call( "{$controller}@destroy", [ $resourceId ] );
    }

    /**
     * @param string $slug
     * @param mixed $dialog
     * @return Response
     * @throws HttpException
     */
    public function dialog( $slug, $dialog )
    {
        $controller = $this->findControllerBySlug( $slug );

        return $this->app->call( "{$controller}@dialog", [ $dialog ] );
    }

    /**
     * @param string $slug
     * @param mixed $api
     * @return mixed
     * @throws HttpException
     */
    public function api( $slug, $api )
    {
        $controller = $this->findControllerBySlug( $slug );

        return $this->app->call( "{$controller}@api", [ $api ] );
    }

    /**
     * @param string $slug
     * @return string
     * @throws HttpException
     */
    public function findControllerBySlug( $slug )
    {
        /* @var $modules ModuleRegistry */
        $modules = app( 'leaf.modules' );

        $module = $modules->findCrudModuleByName( $slug );

        if( !$module )
        {
            $this->app->abort( Response::HTTP_NOT_FOUND );
        }

        return $module->getControllerClass();
    }
}
