<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Admin\Form;
use CubeSystems\Leaf\Admin\Form\Fields\Text;
use CubeSystems\Leaf\Admin\Grid;
use CubeSystems\Leaf\Admin\Tools\ToolboxMenu;
use CubeSystems\Leaf\Admin\Traits\Crudify;
use CubeSystems\Leaf\Support\Translate\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Waavi\Translation\Repositories\LanguageRepository;

class LanguageController extends Controller
{
    use Crudify;

    /**
     * @var string
     */
    protected $resource = Language::class;

    /**
     * @var LanguageRepository
     */
    protected $repository;

    /**
     * @param LanguageRepository $repository
     */
    public function __construct(
        LanguageRepository $repository
    )
    {
        $this->repository = $repository;
    }

    /**
     * @param Model $model
     * @return Form
     */
    public function form( Model $model )
    {
        return $this->module()->form( $model, function( Form $form )
        {
            $form->addField( new Text( 'locale' ) )->rules( 'required' );
            $form->addField( new Text( 'name' ) )->rules( 'required' );
        } );
    }

    /**
     * @return Grid
     */
    public function grid()
    {
        $grid = $this->module()->grid( $this->resource(), function( Grid $grid )
        {
            $grid->column( 'locale' );
            $grid->column( 'name' );
        } );

        return $grid
            ->items( Language::withTrashed()->get() )
            ->paginate( false );
    }

    /**
     * @param int $resourceId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function restore( $resourceId )
    {
        /** @var Language $resource */
        $resource = $this->resource()->withTrashed()->findOrFail( $resourceId );

        $resource->restore();

        return redirect( $this->module()->url( 'index' ) );
    }

    /**
     * @param int $resourceId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function disable( $resourceId )
    {
        /** @var Language $resource */
        $resource = $this->resource()->findOrFail( $resourceId );

        $resource->delete();

        return redirect( $this->module()->url( 'index' ) );
    }

    /**
     * @param $resourceId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy( $resourceId )
    {
        $resource = $this->resource()->withTrashed()->findOrFail( $resourceId );

        $resource->forceDelete();

        return redirect( $this->module()->url( 'index' ) );
    }

    /**
     * @param \CubeSystems\Leaf\Admin\Tools\ToolboxMenu $tools
     */
    protected function toolbox( ToolboxMenu $tools )
    {
        /** @var Language $model */
        $model = $tools->model();

        $tools->add( 'edit', $this->url( 'edit', $model->getKey() ) );

        if( $model->trashed() )
        {
            $tools->add( 'restore', $this->url( 'dialog', [ 'dialog' => 'confirm_restore', 'id' => $model->getKey() ] ) )->dialog();
            $tools->add( 'delete', $this->url( 'dialog', [ 'dialog' => 'confirm_delete', 'id' => $model->getKey() ] ) )->dialog()->danger();
        }
        else
        {
            $tools->add( 'disable', $this->url( 'dialog', [ 'dialog' => 'confirm_disable', 'id' => $model->getKey() ] ) )->dialog()->danger();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function confirmDisableDialog( Request $request )
    {
        $resourceId = $request->get( 'id' );
        $model = $this->resource()->find( $resourceId );

        return view( 'leaf::dialogs.confirm_disable', [
            'form_target' => $this->url( 'disable', [ $resourceId ] ),
            'list_url' => $this->url( 'index' ),
            'object_name' => (string) $model,
        ] );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function confirmRestoreDialog( Request $request )
    {
        $resourceId = $request->get( 'id' );
        $model = $this->resource()->withTrashed()->find( $resourceId );

        return view( 'leaf::dialogs.confirm_restore', [
            'form_target' => $this->url( 'restore', [ $resourceId ] ),
            'list_url' => $this->url( 'index' ),
            'object_name' => (string) $model,
        ] );
    }
}