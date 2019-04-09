<?php

namespace Arbory\Base\Admin\Grid;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class Filter
 * @package Arbory\Base\Admin\Grid
 */
class Filter implements FilterInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var QueryBuilder
     */
    protected $query;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var bool
     */
    protected $paginated = true;

    /**
     * @var int
     */
    protected $perPage;

    /**
     * Filter constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->query = $model->newQuery();
        $this->request = request();
    }

    /**
     * @param Collection|Column[] $columns
     * @return void
     */
    protected function order(Collection $columns)
    {
        $orderBy = $this->request->get('_order_by');
        $orderDirection = $this->request->get('_order', 'asc');

        if (!$orderBy) {
            return;
        }

        $column = $columns->filter(function (Column $column) {
            return $column->isSortable();
        })->filter(function (Column $column) use ($orderBy) {
            return $column->getName() === $orderBy;
        })->first();

        if (!$column) {
            return;
        }

        $this->query->orderBy($column->getName(), $orderDirection);
    }

    /**
     * @param Collection $columns
     */
    protected function filter(Collection $columns)
    {
        $filterParameters = self::removeNonFilterParameters($this->request->all());

        foreach ($filterParameters as $getKey => $getValue) {
            if (!$getValue) {
                continue;
            }

            $column = $columns->filter(function (Column $column) use ($getKey) {
                return $column->getName() === $getKey || $column->getRelationName() === $getKey;
            })->first();

            if (!$column || !$column->getHasFilter()) {
                continue;
            }

            $this->createQuery($column, $getValue, $getKey);
        }
    }

    /**
     * @param $phrase
     * @param Collection|Column[] $columns
     */
    protected function search($phrase, $columns)
    {
        $keywords = explode(' ', $phrase);

        foreach ($keywords as $string) {
            $this->query->where(function (QueryBuilder $query) use ($string, $columns) {
                foreach ($columns as $column) {
                    if (!$column->isSearchable()) {
                        continue;
                    }

                    $column->searchConditions($query, $string);
                }
            });
        }
    }

    /**
     * @return Collection|LengthAwarePaginator
     */
    protected function loadItems()
    {
        $result = $this->query;

        if (!$this->isPaginated()) {
            return $result->get();
        }

        /** @var LengthAwarePaginator $result */
        $result = $this->query->paginate($this->getPerPage());

        if ($this->request->has('search')) {
            $result->appends([
                'search' => $this->request->get('search'),
            ]);
        }

        if ($this->request->has('_order_by') && $this->request->has('_order')) {
            $result->appends([
                '_order_by' => $this->request->get('_order_by'),
                '_order' => $this->request->get('_order'),
            ]);
        }

        return $result;
    }

    /**
     * @param Collection|Column[] $columns
     * @return Collection|LengthAwarePaginator
     */
    public function execute(Collection $columns)
    {
        if ($this->request->has('search')) {
            $this->search($this->request->get('search'), $columns);
        }

        $this->filter($columns);

        $this->order($columns);

        return $this->loadItems();
    }

    /**
     * @param $relationName
     */
    public function withRelation($relationName)
    {
        $this->query->with($relationName);
    }

    /**
     * @return QueryBuilder
     */
    public function getQuery(): QueryBuilder
    {
        return $this->query;
    }

    public function createQuery($column, $value, $key)
    {
        $filterAction = $this->getFilterTypeAction($column);

        if (is_null($column->getRelationName())) {
            $this->createQueryWithoutRelation($column->getFilterColumnName($key), $filterAction, $value);
        } else {
            $this->createQueryWithRelation($column, $filterAction, $value);
        }
    }

    /**
     * @return bool
     */
    public function isPaginated(): bool
    {
        return $this->paginated;
    }

    /**
     * @param bool $paginated
     */
    public function setPaginated(bool $paginated)
    {
        $this->paginated = $paginated;
    }

    /**
     * @return int|null
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @param $column
     * @return mixed
     */
    public function getFilterTypeAction($column) {
        return $column->getFilterType()->getAction();
    }

    /**
     * @param int $perPage
     */
    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;
    }

    /**
     * @param $columnName
     * @param $actions
     * @param $values
     */
    public function createQueryWithoutRelation($columnName, $actions, $values)
    {
        $actions = array_wrap($actions);
        $values = array_wrap($values);

        foreach (array_combine($values, $actions) as $value => $action) {
            $this->query->where($columnName, $action, $value);
        }
    }

    /**
     * @param $column
     * @param $actions
     * @param $values
     */
    public function createQueryWithRelation($column, $actions, $values)
    {
        $actions = array_wrap($actions);
        $values = array_wrap($values);

        if (count($actions) === count($values)) {
            foreach (array_combine($values, $actions) as $value => $action) {
                $this->query->whereHas($column->getRelationName(), function ($query) use ($column, $action, $value) {
                    $query->where($column->getFilterRelationColumn(), $action, $value);
                });
            }

            return;
        }

        $this->query->whereHas($column->getRelationName(), function ($query) use ($column, $values) {
            $query->whereIn($column->getFilterRelationColumn(), $values);
        });
    }

    /**
     * @param $parameters
     * @return array
     */
    private function removeNonFilterParameters($parameters)
    {
        unset($parameters['_order_by']);
        unset($parameters['_order']);

        return self::recursiveArrayFilter($parameters);
    }

    /**
     * @param array $filterParameters
     * @return array
     */
    private function recursiveArrayFilter(array $filterParameters)
    {
        foreach ($filterParameters as $getKey => &$getValue) {
            if (is_array($getValue)) {
                $getValue = self::recursiveArrayFilter($getValue);
            }
        }

        return array_filter($filterParameters);
    }

    /**
     * @param $actions
     * @param $values
     * @return array|false
     */
    public static function arrayCombine($actions, $values)
    {
        return count($values) === 1 ? array_combine($values, $actions) : self::arrayCombineUnequal($actions, $values);
    }

    /**
     * @param $actions
     * @param $values
     * @return array|false
     */
    public static function arrayCombineUnequal($actions, $values)
    {
        $action = $actions[0];

        for ($actionNumber = 1; $actionNumber < count($values); $actionNumber++) {
            array_push($actions, $action);
        }

        return array_combine($values, $actions);
    }
}
