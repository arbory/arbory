<?php

namespace CubeSystems\Leaf\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GenericRepository
 * @package CubeSystems\Leaf\Repositories
 */
abstract class GenericRepository
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * GenericRepository constructor.
     * @param $resource
     */
    public function __construct( $resource )
    {
        $this->makeModel( $resource );
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model $model
     */
    public function setModel( $model )
    {
        $this->model = $model;
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function all( array $columns = [ '*' ] )
    {
        return $this->model->newQuery()->get( $columns );
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate( $perPage = 15, array $columns = [ '*' ] )
    {
        return $this->model->newQuery()->paginate( $perPage, $columns );
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create( array $data )
    {
        return $this->model->create( $data );
    }

    /**
     * @param array $data
     * @param $itemId
     * @param string $attribute
     * @return mixed
     */
    public function update( array $data, $itemId, $attribute = 'id' )
    {
        return $this->newQuery()->where( $attribute, '=', $itemId )->update( $data );
    }

    /**
     * @param $itemId
     * @return mixed
     */
    public function delete( $itemId )
    {
        return $this->model->destroy( $itemId );
    }

    /**
     * @param $itemId
     * @param array $columns
     * @return mixed
     */
    public function find( $itemId, array $columns = [ '*' ] )
    {
        return $this->newQuery()->find( $itemId, $columns );
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy( $attribute, $value, array $columns = [ '*' ] )
    {
        return $this->newQuery()->where( $attribute, '=', $value )->first( $columns );
    }

    /**
     * @param $itemId
     * @param array $columns
     * @return Model
     */
    public function findOrNew( $itemId, array $columns = [ '*' ] )
    {
        return $this->newQuery()->findOrNew( $itemId, $columns );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        return $this->getModel()->newQuery();
    }

    /**
     * @param $class
     * @return Model
     */
    protected function makeModel( $class )
    {
        $this->model = app()->make( $class );
    }
}
