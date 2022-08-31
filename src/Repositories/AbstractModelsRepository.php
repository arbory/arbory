<?php

namespace Arbory\Base\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AbstractModelRepository.
 */
abstract class AbstractModelsRepository
{
    /**
     * @var string
     */
    protected $modelClass;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * GenericRepository constructor.
     */
    public function __construct()
    {
        $this->makeModel($this->modelClass);
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    public function all(array $columns = ['*'])
    {
        return $this->newQuery()->get($columns);
    }

    /**
     * @param  int  $perPage
     * @return mixed
     */
    public function paginate($perPage = 15, array $columns = ['*'])
    {
        return $this->newQuery()->paginate($perPage, $columns);
    }

    /**
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->getModel()->create($data);
    }

    /**
     * @param $itemId
     * @param  string  $attribute
     * @return mixed
     */
    public function update(array $data, $itemId, $attribute = 'id')
    {
        return $this->newQuery()->where($attribute, '=', $itemId)->update($data);
    }

    /**
     * @param $arboryFileId
     * @return mixed
     */
    public function delete($arboryFileId)
    {
        return $this->getModel()->destroy($arboryFileId);
    }

    /**
     * @param $itemId
     * @return mixed
     */
    public function find($itemId, array $columns = ['*'])
    {
        return $this->newQuery()->find($itemId, $columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function findBy($attribute, $value, array $columns = ['*'])
    {
        return $this->newQuery()->where($attribute, '=', $value)->get();
    }

    /**
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function findOneBy($attribute, $value, array $columns = ['*'])
    {
        return $this->newQuery()->where($attribute, '=', $value)->first();
    }

    /**
     * @param $itemId
     * @return Model
     */
    public function findOrNew($itemId, array $columns = ['*'])
    {
        return $this->newQuery()->findOrNew($itemId, $columns);
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
     */
    protected function makeModel($class)
    {
        $this->model = new $class;
    }
}
