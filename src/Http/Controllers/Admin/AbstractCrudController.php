<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Breadcrumbs;
use CubeSystems\Leaf\Builder\FormBuilder;
use CubeSystems\Leaf\Builder\IndexBuilder;
use CubeSystems\Leaf\Menu\Item;
use CubeSystems\Leaf\FieldSet;
use CubeSystems\Leaf\Repositories\ResourcesRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Lang;
use Redirect;
use Response;
use Session;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AbstractCrudController
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @var string
     */
    protected $resource;

    /**
     * @var ResourcesRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $indexView = 'leaf::controllers.resource.index';

    /**
     * @var string
     */
    protected $filterView = 'leaf::controllers.resource.filter';

    /**
     * @var string
     */
    protected $formView = 'leaf::controllers.resource.form';


    /**
     * ResourceController constructor.
     * @param Application $app
     */
    public function __construct( Application $app )
    {
        $this->app = $app;
        $this->repository = new ResourcesRepository( $this->resource );
    }

    /**
     * @return string
     */
    public function getIndexView()
    {
        return $this->indexView;
    }

    /**
     * @param string $indexView
     * @return $this
     */
    public function setIndexView( $indexView )
    {
        $this->indexView = $indexView;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilterView()
    {
        return $this->filterView;
    }

    /**
     * @param string $filterView
     * @return $this
     */
    public function setFilterView( $filterView )
    {
        $this->filterView = $filterView;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormView()
    {
        return $this->formView;
    }

    /**
     * @param string $formView
     * @return $this
     */
    public function setFormView( $formView )
    {
        $this->formView = $formView;

        return $this;
    }

    /**
     * @return Model|string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return null|string
     */
    public function getSlug()
    {
        /**
         * @var $menuItem Item
         */
        $menuItem = $this->app['leaf.menu']->findItemByController( static::class );

        if( !$menuItem )
        {
            return null;
        }

        return $menuItem->getSlug();
    }

    public function getBreadcrumbs()
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add(
            Lang::get( 'leaf.breadcrumbs.home' ),
            route( 'admin.dashboard' )
        );
        $breadcrumbs->add(
            $this->app['leaf.menu']->findItemByController( static::class )->getTitle(),
            route( 'admin.model.index', $this->getSlug() )
        );

        return $breadcrumbs;
    }

    /**
     *
     */
    public function buildFilters()
    {
        // TODO: Build filter block
    }

    /**
     * @param null $resourceId
     * @return FormBuilder
     */
    protected function getFormBuilder( $resourceId = null )
    {
        $model = $this->repository->findOrNew( $resourceId );

        $builder = new FormBuilder( $model );
        $fieldSet = new FieldSet;

        if( method_exists( $this, 'formFields' ) )
        {
            $this->formFields( $fieldSet );
        }

        $builder
            ->setFieldSet( $fieldSet )
            ->setController( $this );

        return $builder;
    }

    /**
     * @return IndexBuilder
     */
    protected function getIndexBuilder()
    {
        $builder = new IndexBuilder( $this->repository );
        $builder->setController( $this );

        return $builder;
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index( Request $request )
    {
        $fieldSet = new FieldSet;

        if( method_exists( $this, 'indexFields' ) )
        {
            $this->indexFields( $fieldSet );
        }

        $builder = $this->getIndexBuilder();
        $builder->setFieldSet( $fieldSet );
        $builder->setResource( $this->getResource() );
        $builder->setParameters( $request->input() );

        $this->buildFilters();

        $results = $builder->build();

        return view( $this->getIndexView(), [
            'controller' => $this,
            'field_set' => $fieldSet,
            'results' => $results,
            'paginator' => $results->getPaginator(),
            'breadcrumbs' => $this->getBreadcrumbs()->get(),
        ] );
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $builder = $this->getFormBuilder();
        $result = $builder->build();
        $slug = $this->getSlug();

        $breadcrumbs = $this->getBreadcrumbs();
        $breadcrumbs->add(
            Lang::get( 'leaf.breadcrumbs.new_item' ),
            route( 'admin.model.create', [ $slug ] )
        );

        return view( $this->getFormView(), [
            'slug' => $this->getSlug(),
            'title' => Lang::get( 'leaf.resources.create_new' ),
            'result' => $result,
            'breadcrumbs' => $breadcrumbs->get(),
        ] );
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store( Request $request )
    {
        $name = $this->getSlug();
        $form = $this->getFormBuilder();
        $result = $form->create( $request->input() );

        // Check validation errors
        if( get_class( $result ) === Validator::class )
        {
            Session::flash( 'message.error', Lang::get( 'leaf.messages.error.validation-errors' ) );

            return Redirect::route( 'admin.model.create', [ $name ] )
                ->withInput()
                ->withErrors( $result );
        }

        // TODO: Redirect support

        Session::flash( 'message.success', Lang::get( 'leaf.messages.success.model-created', [
            'model' => $name
        ] ) );

        return Redirect::route( 'admin.model.index', $name );
    }

    /**
     * @param $resourceId
     * @return \Illuminate\View\View
     */
    public function edit( $resourceId )
    {
        $slug = $this->getSlug();

        $builder = $this->getFormBuilder( $resourceId );
        $result = $builder->build();

        $breadcrumbs = $this->getBreadcrumbs();
        $breadcrumbs->add(
            (string) $builder->getModel(),
            route( 'admin.model.edit', [ $slug, $resourceId ] )
        );

        return view( $this->getFormView(), [
            'id' => $resourceId,
            'slug' => $slug,
            'title' => (string) $builder->getModel(),
            'result' => $result,
            'breadcrumbs' => $breadcrumbs->get()
        ] );
    }

    /**
     * @param Request $request
     * @param $resourceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( Request $request, $resourceId )
    {
        $name = $this->getSlug();

        $form = $this->getFormBuilder( $resourceId );

        $result = $form->update( $request->input() );

        // Check validation errors
        if( get_class( $result ) === Validator::class )
        {
            Session::flash( 'message.error', Lang::get( 'leaf.messages.error.validation-errors' ) );

            // TODO: Handle ajax response
            /**
             * @var $result Validator
             */

            $ajaxResponse = [];

            foreach( $result->errors()->getMessages() as $field => $errors )
            {
                foreach( $errors as $error )
                {
                    $ajaxResponse['resource[' . $field . ']'][] = [
                        'message' => $error,
                        'full_message' => $error,
                        'error_code' => '',
                    ];
                }
            }

            return Response::json( [
                'errors' => $ajaxResponse,
            ], 422 );
        }

        Session::flash( 'message.success', Lang::get( 'leaf.messages.success.model-updated', [
            'model' => $name
        ] ) );

        return Redirect::route( 'admin.model.index', $this->getSlug() );
    }

    /**
     * @param $resourceId
     * @return \Illuminate\Http\Response
     * @throws HttpException
     */
    public function destroy( $resourceId )
    {
        $name = $this->getSlug();

        $form = $this->getFormBuilder( $resourceId );

        try
        {
            $response = $form->destroy();
        }
        catch( \Exception $e )
        {
            $this->app->abort( \Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY, $e->getMessage() );

            return null;
        }

        if( $response instanceof \Illuminate\Http\Response )
        {
            return $response;
        }

        Session::flash( 'message.success', Lang::get( 'leaf.messages.success.model-updated', [
            'model' => $name
        ] ) );

        return Redirect::route( 'admin.model.index', $name );
    }

    /**
     * @param $name
     * @return \Illuminate\View\View
     * @throws HttpException
     */
    public function dialog( $name )
    {
        $handler = camel_case( $name ) . 'Dialog';

        if( !$name || !method_exists( $this, $handler ) )
        {
            $this->app->abort( \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND );

            return null;
        }

        return $this->{$handler}();
    }

    /**
     * @return \Illuminate\View\View
     */
    protected function toolboxDialog()
    {
        $resourceId = $this->app['request']->get( 'id' );

        return view( 'leaf::dialogs.toolbox', [
            'confirm_destroy_url' => route( 'admin.model.dialog', [
                'model' => $this->getSlug(),
                'dialog' => 'confirm_destroy',
                'id' => $resourceId,
            ] ),
        ] );
    }

    /**
     * @return \Illuminate\View\View
     */
    protected function confirmDestroyDialog()
    {
        $resourceId = $this->app['request']->get( 'id' );
        $model = $this->repository->find( $resourceId );
        $slug = $this->getSlug();

        return view( 'leaf::dialogs.confirm_delete', [
            'form_target' => route( 'admin.model.destroy', [ $slug, $resourceId ] ),
            'list_url' => route( 'admin.model.index', $slug ),
            'object_name' => (string) $model,
        ] );
    }

}
