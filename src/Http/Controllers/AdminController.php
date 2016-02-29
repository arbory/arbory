<?php

namespace CubeSystems\Leaf\Http\Controllers;

use CubeSystems\Leaf\Breadcrumbs;
use CubeSystems\Leaf\Builder\FormBuilder;
use CubeSystems\Leaf\Builder\IndexBuilder;
use CubeSystems\Leaf\Menu\Item;
use CubeSystems\Leaf\Scheme;
use Eloquent;
use Illuminate\Validation\Validator;
use Input;
use Lang;
use Redirect;
use Response;
use Session;

abstract class AdminController
{
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
        $menuItem = app( 'leaf.menu' )->findItemByController( static::class );

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
            app( 'leaf.menu' )->findItemByController( static::class )->getTitle(),
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
     * @return Scheme
     */
    protected function createScheme()
    {
        return new Scheme( $this->getResource(), $this );
    }

    /**
     * @param null $id
     * @return FormBuilder
     */
    protected function getFormBuilder( $id = null )
    {
        $builder = new FormBuilder;
        $scheme = $this->createScheme();

        $this->formFields( $scheme );

        $builder->setScheme( $scheme )
            ->setResource( $this->getResource() )
            ->setController( $this )
            ->setIdentifier( $id )
            ->setContext( $id === null ? FormBuilder::CONTEXT_CREATE : FormBuilder::CONTEXT_EDIT );

        return $builder;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $scheme = $this->createScheme();

        $this->indexFields( $scheme );

        $builder = new IndexBuilder;
        $builder->setScheme( $scheme );
        $builder->setResource( $this->getResource() );

        $this->buildFilters();

        $results = $builder->build();

        return view( $this->getIndexView(), [
            'controller' => $this,
            'scheme' => $scheme,
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
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $name = $this->getSlug();
        $form = $this->getFormBuilder();
        $result = $form->create( Input::all() );

        // Check validation errors
        if( get_class( $result ) === Validator::class )
        {
            Session::flash( 'message.error', trans( 'leaf::messages.error.validation-errors' ) );

            return Redirect::route( 'admin.model.create', [ $name ] )
                ->withInput()
                ->withErrors( $result );
        }

        // TODO: Redirect support

        // Set the flash message
        Session::flash( 'message.success', trans( 'leaf::messages.success.model-created', [
            'model' => $name
        ] ) );

        return Redirect::route( 'admin.model.index', $name );
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit( $id )
    {
        $slug = $this->getSlug();

        $builder = $this->getFormBuilder( $id );
        $result = $builder->build();

        $breadcrumbs = $this->getBreadcrumbs();
        $breadcrumbs->add(
            (string) $builder->getModel(),
            route( 'admin.model.edit', [ $slug, $id ] )
        );

        return view( $this->getFormView(), [
            'id' => $id,
            'slug' => $slug,
            'result' => $result,
            'breadcrumbs' => $breadcrumbs->get()
        ] );
    }

    /**
     * @param $id
     * @return $this|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update( $id )
    {
        $name = $this->getSlug();

        $form = $this->getFormBuilder( $id );

        $result = $form->update( Input::all() );

        // Check validation errors
        if( get_class( $result ) === Validator::class )
        {
            Session::flash( 'message.error', trans( 'leaf::messages.error.validation-errors' ) );

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

        // Set the flash message
        Session::flash( 'message.success', trans( 'leaf::messages.success.model-updated', [
            'model' => $name
        ] ) );

        return Redirect::route( 'admin.model.index', $this->getSlug() );
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function confirmDestroy( $id )
    {
        $slug = $this->getSlug();
        $class = $this->getResource();
        $model = $class::find( $id );

        return view( 'leaf::modals.confirm_delete', [
            'form_target' => route( 'admin.model.destroy', [ $slug, $id ] ),
            'list_url' => route( 'admin.model.index', $slug ),
            'object_name' => (string) $model,
        ] );
    }

    /**
     * @param $id
     * @return FormBuilder|\Illuminate\Http\RedirectResponse|null
     */
    public function destroy( $id )
    {
        $name = $this->getSlug();

        $form = $this->getFormBuilder( $id );

        $response = $form->destroy();

        if( $response instanceof \Illuminate\Http\Response )
        {
            return $response;
        }

        Session::flash( 'message.success', trans( 'leaf::messages.success.model-updated', [
            'model' => $name
        ] ) );

        return Redirect::route( 'admin.model.index', $name );
    }

    public function handleGetAction( $id, $action )
    {
        $url = route( 'admin.model.confirm_destroy', [ $this->getSlug(), $id ] );

        // TODO: Builder + view
        return '<li><a class="button ajaxbox danger" title="Delete" href="' . e( $url ) . '" data-modal="true">' . trans( 'Delete' ) . '</a></li>';
    }

    /**
     * @param Scheme $scheme
     */
    public function indexFields( Scheme $scheme )
    {

    }

    /**
     * @param Scheme $scheme
     */
    public function filterFields( Scheme $scheme )
    {

    }

    /**
     * @param Scheme $scheme
     */
    public function formFields( Scheme $scheme )
    {

    }

    /**
     * @param $slug
     * @return null|string
     */
    public static function getClassFromSlug( $slug )
    {
        /**
         * @var $menuItem Item
         */
        $menuItem = app( 'leaf.menu' )->findItemBySlug( $slug );

        if( !$menuItem )
        {
            return null;
        }

        return $menuItem->getController();
    }

}
