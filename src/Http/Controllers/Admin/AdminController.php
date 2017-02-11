<?php

namespace CubeSystems\Leaf\Http\Controllers\Admin;

use CubeSystems\Leaf\Breadcrumbs;
use CubeSystems\Leaf\CRUD\ResourceRepository;
use CubeSystems\Leaf\CRUD\ResourceRoutes;
use CubeSystems\Leaf\FieldSet;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Lang;

class AdminController extends Controller
{
    protected $resource;

    public function indexFields()
    {
        return new FieldSet();
    }

    public function formFields()
    {
        return new FieldSet();
    }

    public function index( Request $request )
    {
        $fieldSet = $this->indexFields();

        $repository = new ResourceRepository( $this->resource, $fieldSet );

        $routes = new ResourceRoutes( $this );

        return view( 'leaf::controllers.resource.index_2', [
            'page' => $repository->all( $request ),
            'fields' => $fieldSet,
            'routes' => $routes,
            'breadcrumbs' => $this->getBreadcrumbs()->get(),
        ] );
    }

    public function edit( $resourceId )
    {
        $repository = new ResourceRepository( $this->resource, $this->formFields() );
        $resource = $repository->find( $resourceId );

        $routes = new ResourceRoutes( $this );

        return view( 'leaf::controllers.resource.form_2', [
            'id' => $resource->getIdentifier(),
            'routes' => $routes,
            'breadcrumbs' => $this->getBreadcrumbs()->get(),
            'resource' => $resource,
        ] );
    }

    public function update( Request $request, $resourceId )
    {
        $repository = new ResourceRepository( $this->resource, $this->formFields() );
        $resource = $repository->find( $resourceId );
        $resource->update( $request );

        $routes = new ResourceRoutes( $this );

        return redirect( $routes->getUrl( 'index' ) );
    }

    public function create()
    {
        $repository = new ResourceRepository( $this->resource, $this->formFields() );
        $resource = $repository->create();

        return view( 'create', [
            'resource' => $resource,
        ] );
    }

    public function store( \App\Http\Requests\CreateBearRequest $request )
    {
        $repository = new ResourceRepository( $this->resource, $this->formFields() );
        $resource = $repository->create();
        $resource->store( $request );

        $routes = new ResourceRoutes( $this );

        return redirect( $routes->getUrl( 'index' ) );
    }

    public function destroy( $resourceId )
    {
        $repository = new ResourceRepository( $this->resource, $this->formFields() );
        $resource = $repository->find( $resourceId );

        $resource->destroy();

        return redirect();
    }


    public function getBreadcrumbs()
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add(
            Lang::get( 'leaf.breadcrumbs.home' ),
            route( 'admin.dashboard' )
        );
//        $breadcrumbs->add(
//            $this->app['leaf.menu']->findItemByController( static::class )->getTitle(),
//            route( 'admin.model.index', $this->getSlug() )
//        );

        return $breadcrumbs;
    }
}
