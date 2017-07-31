<?php

namespace Arbory\Base\Admin\Traits;

use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Layout;
use Arbory\Base\Admin\Module;
use Arbory\Base\Admin\Tools\ToolboxMenu;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

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
            $this->module =\Admin::modules()->findModuleByControllerClass( get_class( $this ) );
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
        $form = $this->form( $this->resource() );

        $request->request->add( [ 'fields' => $form->fields() ] );

        $form->validate();

        if( $request->ajax() )
        {
            return response()->json( [ 'ok' ] );
        }

        $form->store( $request );

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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update( Request $request, $resourceId )
    {
        $resource = $this->findOrNew( $resourceId );
        $form = $this->form( $resource );

        $request->request->add( [ 'fields' => $form->fields() ] );

        $form->validate();

        if( $request->ajax() )
        {
            return response()->json( [ 'ok' ] );
        }

        $form->update( $request );

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
     * @param Request $request
     * @param string $as
     * @return mixed
     * @throws \PHPExcel_Exception
     */
    public function export( Request $request, $as )
    {
        $grid = $this->grid();

        \Excel::create( snake_case( class_basename( $this ) ), function( LaravelExcelWriter $excel ) use ( $grid )
        {
            $excel->sheet( 'Worksheet', function( LaravelExcelWorksheet $sheet ) use ( $grid )
            {
                $sheet->fromArray( $grid->toArray() );
            } );
        } )->export( $as );
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function toolboxDialog( Request $request )
    {
        $node = $this->findOrNew( $request->get( 'id' ) );

        $toolbox = new ToolboxMenu( $node );

        $this->toolbox( $toolbox );

        return $toolbox->render();
    }

    /**
     * @param \Arbory\Base\Admin\Tools\ToolboxMenu $tools
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

        return view( 'arbory::dialogs.confirm_delete', [
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
     * @return string
     */
    public function url( $route, $parameters = [] )
    {
        return $this->module()->url( $route, $parameters );
    }

    /**
     * @param mixed $resourceId
     * @return Model
     */
    protected function findOrNew( $resourceId ): Model
    {
        /**
         * @var Model $resource
         */
        $resource = $this->resource();

        if( method_exists( $resource, 'bootSoftDeletes' ) )
        {
            $resource = $resource->withTrashed();
        }

        $resource = $resource->findOrNew( $resourceId );
        $resource->setAttribute( $resource->getKeyName(), $resourceId );

        return $resource;
    }

    /**
     * @param Request $request
     * @return array|Request|string
     */
    public function slugGeneratorApi(Request $request)
    {
        return str_slug( request( 'from' ) );
    }
}
