<?php

namespace CubeSystems\Leaf\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;


/**
 * Class ResourceController
 * @package CubeSystems\Leaf\Http\Controllers
 */
class ResourceController extends Controller
{
    /**
     * @param  string $name
     * @return Response
     */
    public function index( $name )
    {
        return \App::call( AdminController::getClassFromSlug( $name ) . '@index' );
    }

    /**
     * @param  string $name
     * @return Response
     */
    public function create( $name )
    {
        return \App::call( AdminController::getClassFromSlug( $name ) . '@create' );
    }

    /**
     * @param  string $name
     * @return Response
     */
    public function store( $name )
    {
        return \App::call( AdminController::getClassFromSlug( $name ) . '@store' );
    }

    /**
     * @param  string $name
     * @param  int $id
     * @return Response
     */
    public function edit( $name, $id )
    {
        return \App::call( AdminController::getClassFromSlug( $name ) . '@edit', [ $id ] );
    }

    /**
     * @param  string $name
     * @param  int $id
     * @return Response
     */
    public function update( $name, $id )
    {
        return \App::call( AdminController::getClassFromSlug( $name ) . '@update', [ $id ] );
    }

    /**
     * @param $name
     * @param $id
     * @return Response
     */
    public function confirmDestroy( $name, $id )
    {
        return \App::call( AdminController::getClassFromSlug( $name ) . '@confirmDestroy', [ $id ] );
    }

    /**
     * @param $name
     * @param $id
     * @return Response
     */
    public function destroy( $name, $id )
    {
        return \App::call( AdminController::getClassFromSlug( $name ) . '@destroy', [ $id ] );
    }

    /**
     * @param $name
     * @param $id
     * @param $action
     * @return Response
     */
    public function handleGetAction( $name, $id, $action )
    {
        return \App::call( AdminController::getClassFromSlug( $name ) . '@handleGetAction', [ $id, $action ] );
    }

    /**
     * @param $name
     * @param $id
     * @param $action
     * @return Response
     */
    public function handlePostAction( $name, $id, $action )
    {
        return \App::call( AdminController::getClassFromSlug( $name ) . '@handlePostAction', [ $id, $action ] );
    }

}
