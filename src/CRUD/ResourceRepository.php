<?php

namespace CubeSystems\Leaf\CRUD;

use CubeSystems\Leaf\Fields\FieldInterface;
use CubeSystems\Leaf\FieldSet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 * Class ResourceRepository
 * @package CubeSystems\Leaf\CRUD
 */
class ResourceRepository
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var FieldSet|FieldInterface[]
     */
    protected $fieldSet;

    /**
     * ResourceRepository constructor.
     * @param $class
     * @param FieldSet $fieldSet
     */
    public function __construct( $class, FieldSet $fieldSet )
    {
        $this->class = $class;
        $this->fieldSet = $fieldSet;
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function all( Request $request )
    {
        $query = $this->newQuery();

        if( $request->has( 'search' ) )
        {
            $this->handleSearchParams( $query, $request );
        }

        $page = Paginator::resolveCurrentPage();
        $perPage = config( 'leaf.pagination.items_per_page', $this->model()->getPerPage() );

        $collection = new Collection();

        $paginator = new LengthAwarePaginator(
            $collection,
            $query->toBase()->getCountForPagination(),
            $perPage,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );

        if( $request->has( '_order_by' ) && $request->has( '_order' ) )
        {
            $query->orderBy( $request->get( '_order_by' ), $request->get( '_order' ) );

            $paginator->addQuery( '_order_by', $request->get( '_order_by' ) );
            $paginator->addQuery( '_order', $request->get( '_order' ) );
        }

        if( $request->has( 'search' ) )
        {
            $paginator->addQuery( 'search', $request->get( 'search' ) );
        }

        foreach( $query->forPage( $page, $perPage )->get() as $item )
        {
            $resource = new Resource( $item, $this->fieldSet );

            $collection->push( $resource );
        }

        return $paginator;
    }

    /**
     * @param $resourceId
     * @return \CubeSystems\Leaf\CRUD\Resource|null
     */
    public function find( $resourceId )
    {
        $model = $this->model()->find( $resourceId );

        if( !$model )
        {
            return null;
        }

        return new Resource(
            $model,
            $this->fieldSet
        );
    }

    /**
     * @return \CubeSystems\Leaf\CRUD\Resource
     */
    public function create()
    {
        return new Resource(
            $this->model()->new(),
            $this->fieldSet
        );
    }

    /**
     * return Model
     */
    protected function model()
    {
        $class = $this->class;

        return new $class;
    }

    /**
     * @return Builder
     */
    public function newQuery()
    {
        return $this->model()->newQuery();
    }

    /**
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    protected function handleSearchParams( Builder $query, Request $request )
    {
        $fields = $this->fieldSet;

        $keywords = explode( ' ', $request->get( 'search' ) );

        foreach( $keywords as $string )
        {
            $query->where( function ( Builder $query ) use ( $string, $fields )
            {
                foreach( $fields as $field )
                {
                    $field->searchConditions( $query, $string );
                }
            } );
        }

        return $query;
    }
}
