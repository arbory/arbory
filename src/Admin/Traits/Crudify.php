<?php

namespace CubeSystems\Leaf\Admin\Traits;

use CubeSystems\Leaf\Admin\Form;
use CubeSystems\Leaf\Admin\Grid;
use CubeSystems\Leaf\Admin\Layout;
use CubeSystems\Leaf\Admin\Module;
use CubeSystems\Leaf\Admin\Tools\ToolboxMenu;
use CubeSystems\Leaf\Services\AssetPipeline;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class Crudify
 * @package CubeSystems\Leaf\Admin\Traits
 */
trait Crudify
{
    /**
     * @var Module
     */
    protected $module;

    /**
     * @return Model|Builder
     */
    public function resource()
    {
        $class = $this->resource;

        return new $class;
    }

    /**
     * @return Module
     */
    protected function module()
    {
        if( $this->module === null )
        {
            \App::when( Module::class )->needs( Controller::class )->give( function() {
                return $this;
            } );

            $this->module = \App::make( Module::class );
        }

        return $this->module;
    }

    /**
     * @param Model $model
     * @return Form
     */
    protected function form( Model $model )
    {
        return $this->module()->form( $model, function ( Form $form ) { return $form; } );
    }

    /**
     * @return Grid
     */
    public function grid()
    {
        return $this->module()->grid( $this->resource(), function ( Grid $grid ) { return $grid; } );
    }

    /**
     * @return Layout
     */
    public function index()
    {
        $layout = new Layout( function ( Layout $layout )
        {
            $layout->body( $this->grid( $this->resource() ) );
        } );

        $layout->bodyClass( 'controller-' . str_slug( $this->module()->name() ) . ' view-index' );

        return $layout;
    }

    /**
     * @param $resourceId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show( $resourceId )
    {
        return redirect( $this->module()->url( 'edit', $resourceId ) );
    }

    /**
     * @return Layout
     */
    public function create()
    {
        $layout = new Layout( function ( Layout $layout )
        {
            $layout->body( $this->form( $this->resource() ) );
        } );

        $layout->bodyClass( 'controller-' . str_slug( $this->module()->name() ) . ' view-edit' );

        return $layout;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store( Request $request )
    {
        $this->form( $this->resource() )->store( $request );

        return redirect( $this->module()->url( 'index' ) );
    }

    /**
     * @param $resourceId
     * @return Layout
     */
    public function edit( $resourceId )
    {
        $resource = $this->findOrNew( $resourceId );

        $layout = new Layout( function ( Layout $layout ) use ( $resource )
        {
            $layout->body( $this->form( $resource ) );
        } );

        $layout->bodyClass( 'controller-' . str_slug( $this->module()->name() ) . ' view-edit' );

        return $layout;
    }

    /**
     * @param Request $request
     * @param $resourceId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update( Request $request, $resourceId )
    {
        $resource = $this->findOrNew( $resourceId );

        $this->form( $resource )->update( $request );

        return redirect( $this->module()->url( 'index' ) );
    }

    /**
     * @param $resourceId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy( $resourceId )
    {
        $resource = $this->resource()->findOrFail( $resourceId );

        $this->form( $resource )->destroy();

        return redirect( $this->module()->url( 'index' ) );
    }

    /**
     * @param Request $request
     * @param $name
     * @return mixed
     */
    public function dialog( Request $request, $name )
    {
        $method = camel_case( $name ) . 'Dialog';

        if( !$name || !method_exists( $this, $method ) )
        {
            app()->abort( Response::HTTP_NOT_FOUND );

            return null;
        }

        return $this->{$method}( $request );
    }

    /**
     * @param mixed $resourceId
     * @return Model
     */
    protected function findOrNew( $resourceId ): Model
    {
        $resource = $this->resource()->findOrNew( $resourceId );
        $resource->setAttribute( $resource->getKeyName(), $resourceId );

        return $resource;
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function toolboxDialog( Request $request )
    {
        $node = $this->resource()->findOrFail( $request->get( 'id' ) );

        $toolbox = new ToolboxMenu( $node );

        $this->toolbox( $toolbox );

        return $toolbox->render();
    }

    /**
     * @param \CubeSystems\Leaf\Admin\Tools\ToolboxMenu $tools
     */
    protected function toolbox( ToolboxMenu $tools )
    {
        $model = $tools->model();

        $tools->add( 'edit', $this->url( 'edit', $model->getKey() ) );
        $tools->add( 'delete', $this->url( 'dialog', [ 'dialog' => 'confirm_delete', 'id' => $model->getKey() ] ) )->dialog()->danger();
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    protected function confirmDeleteDialog( Request $request )
    {
        $resourceId = $request->get( 'id' );
        $model = $this->resource()->find( $resourceId );

        return view( 'leaf::dialogs.confirm_delete', [
            'form_target' => $this->url( 'destroy', [ $resourceId ] ),
            'list_url' => $this->url( 'index' ),
            'object_name' => (string) $model,
        ] );
    }

    /**
     * @param Request $request
     * @param $name
     * @return null
     */
    public function api( Request $request, $name )
    {
        $method = camel_case( $name ) . 'Api';

        if( !$name || !method_exists( $this, $method ) )
        {
            app()->abort( Response::HTTP_NOT_FOUND );

            return null;
        }

        return $this->{$method}( $request );
    }

    /**
     * @param $route
     * @param array $parameters
     * @return \CubeSystems\Leaf\Admin\Module\Route
     */
    public function url( $route, $parameters = [] )
    {
        return $this->module()->url( $route, $parameters );
    }
}
