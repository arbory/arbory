<?php

namespace CubeSystems\Leaf\Http\Controllers;

use CubeSystems\Leaf\Breadcrumbs;
use CubeSystems\Leaf\Builder\FormBuilder;
use CubeSystems\Leaf\Builder\IndexBuilder;
use CubeSystems\Leaf\Menu\Item;
use CubeSystems\Leaf\FieldSet;
use Eloquent;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Lang;
use Redirect;
use Response;
use Session;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AdminController
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

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
     * @return Eloquent|string
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
        $builder = new FormBuilder;
        $fieldSet = new FieldSet( $this->getResource(), $this );

        if( method_exists( $this, 'formFields' ) )
        {
            $this->formFields( $fieldSet );
        }

        $builder->setFieldSet( $fieldSet )
            ->setResource( $this->getResource() )
            ->setController( $this )
            ->setIdentifier( $resourceId )
            ->setContext( $resourceId === null ? FormBuilder::CONTEXT_CREATE : FormBuilder::CONTEXT_EDIT );

        return $builder;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index( Request $request )
    {
        $fieldSet = new FieldSet( $this->getResource(), $this );

        if( method_exists( $this, 'indexFields' ) )
        {
            $this->indexFields( $fieldSet );
        }

        $builder = new IndexBuilder;
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
            'result' => $result,
            'breadcrumbs' => $breadcrumbs->get(),
        ] );
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
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
            'result' => $result,
            'breadcrumbs' => $breadcrumbs->get()
        ] );
    }

    /**
     * @param Request $request
     * @param $resourceId
     * @return $this|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
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

            $ajaxResponse = [ ];

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
     * @return \Illuminate\View\View
     */
    public function confirmDestroy( $resourceId )
    {
        $slug = $this->getSlug();
        $class = $this->getResource();
        $model = $class::find( $resourceId );

        return view( 'leaf::modals.confirm_delete', [
            'form_target' => route( 'admin.model.destroy', [ $slug, $resourceId ] ),
            'list_url' => route( 'admin.model.index', $slug ),
            'object_name' => (string) $model,
        ] );
    }

    /**
     * @param $resourceId
     * @return FormBuilder|\Illuminate\Http\RedirectResponse|null
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

    public function handleGetAction( $resourceId, $action )
    {
        $url = route( 'admin.model.confirm_destroy', [ $this->getSlug(), $resourceId ] );

        // TODO: Builder + view
        return '<li><a class="button ajaxbox danger" title="Delete" href="' . e( $url ) . '" data-modal="true">' . Lang::get( 'Delete' ) . '</a></li>';
    }
}
