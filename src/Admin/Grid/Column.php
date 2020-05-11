<?php

namespace Arbory\Base\Admin\Grid;

use Closure;
use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;
use Arbory\Base\Admin\Filter\FilterItem;
use Arbory\Base\Admin\Filter\FilterCollection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

/**
 * Class Column.
 */
class Column
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $relationName;

    /**
     * @var string
     */
    protected $relationColumn;

    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var Closure
     */
    protected $displayer;

    /**
     * @var bool
     */
    protected $sortable = false;

    /**
     * @var bool
     */
    protected $searchable = true;

    /**
     * @var bool
     */
    protected $hasFilter = false;

    /**
     * @var
     */
    protected $filterType;

    /**
     * @var bool
     */
    protected $checkable = false;

    /**
     * @var callable
     */
    protected $customQuery;

    /**
     * @var Closure
     */
    protected $exportColumnDisplay;

    /**
     * Column constructor.
     * @param string $name
     * @param string $label
     */
    public function __construct($name = null, $label = null)
    {
        $this->name = $name;
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getFilterType()
    {
        return $this->filterType;
    }

    /**
     * @return bool
     */
    public function getHasFilter(): bool
    {
        return $this->hasFilter;
    }

    /**
     * @return string
     */
    public function getRelationName()
    {
        return $this->relationName;
    }

    /**
     * @return string
     */
    public function getRelationColumn()
    {
        return $this->relationColumn;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label ?: $this->name;
    }

    /**
     * @return Grid
     */
    public function getGrid(): Grid
    {
        return $this->grid;
    }

    /**
     * @param Grid $grid
     * @return Column
     */
    public function setGrid(Grid $grid)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
     * @param Closure $callable
     * @return Column
     */
    public function display(Closure $callable)
    {
        $this->displayer = $callable;

        return $this;
    }

    /**
     * @param bool $isSortable
     * @return Column
     */
    public function sortable($isSortable = true)
    {
        $this->sortable = $isSortable;

        return $this;
    }

    /**
     * @param bool $isCheckable
     * @return $this
     */
    public function checkable($isCheckable = true)
    {
        $this->checkable = $isCheckable;

        return $this;
    }

    /**
     * @param bool $isSearchable
     * @return Column
     */
    public function searchable($isSearchable = true)
    {
        $this->searchable = $isSearchable;

        return $this;
    }

    /**
     * @param string|null $type
     * @return $this
     */
    public function setFilter($type = null)
    {
        $this->filterType = $type;
        $this->hasFilter = $type !== null;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSortable()
    {
        return $this->sortable && empty($this->relationName);
    }

    /**
     * @return bool
     */
    public function isCheckable()
    {
        return $this->checkable;
    }

    /**
     * @return bool
     */
    public function isSearchable()
    {
        return $this->searchable;
    }

    /**
     * @param callable $query
     * @return $this
     */
    public function setCustomSearchQuery(callable $query)
    {
        $this->customQuery = $query;

        return $this;
    }

    /**
     * @return null|QueryBuilder
     */
    public function getCustomSearchQuery()
    {
        return $this->customQuery;
    }

    /**
     * @param QueryBuilder $query
     * @param $string
     * @return QueryBuilder
     */
    public function searchConditions(QueryBuilder $query, $string)
    {
        if ($this->customQuery) {
            return call_user_func($this->customQuery, $query, $string);
        }

        if ($this->relationName) {
            return $query->orWhereHas($this->relationName, function (QueryBuilder $query) use ($string) {
                $query->where($this->relationColumn, 'like', "%$string%");
            });
        }

        return $query->where($this->getName(), 'like', "%$string%", 'OR');
    }

    /**
     * @param Model $model
     * @return mixed
     */
    protected function getValue(Model $model)
    {
        if ($this->relationName) {
            if ($this->relationName === 'translations') {
                $translation = $model->getTranslation(null, true);

                if (! $translation) {
                    return '';
                }

                return $translation->getAttribute($this->relationColumn);
            }

            $attribute = $model->getAttribute($this->relationName);

            if ($attribute instanceof Model || $attribute instanceof Relation) {
                return $attribute->getAttribute($this->relationColumn);
            }

            return $attribute;
        }

        return $model->getAttribute($this->getName());
    }

    /**
     * @param Model $model
     * @return Element
     */
    public function callDisplayCallback(Model $model)
    {
        $value = $this->getValue($model);

        if ($this->displayer === null) {
            $value = (string) $value;

            if ($url = $this->grid->getRowUrl($model)) {
                return Html::link($value)->addAttributes([
                    'href' => $url,
                ]);
            }

            return Html::span($value);
        }

        return call_user_func_array($this->displayer, [$value, $this, $model]);
    }

    /**
     * @param  \Closure  $closure
     *
     * @return $this
     */
    public function setExportColumnDisplay(Closure $closure): self
    {
        $this->exportColumnDisplay = $closure;

        return $this;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     *
     * @return mixed
     */
    public function getExportColumnDisplay(Model $model)
    {
        if ($this->exportColumnDisplay === null) {
            return $this->callDisplayCallback($model);
        }

        $value = $this->getValue($model);

        return call_user_func($this->exportColumnDisplay, $value, $this, $model);
    }

    /**
     * @param $relationName
     * @param $relationColumn
     */
    public function setRelation($relationName, $relationColumn)
    {
        $this->relationName = $relationName;
        $this->relationColumn = $relationColumn;
    }

    /**
     * @param string $filterType
     * @param iterable $filterTypeConfig
     * @return FilterItem
     */
    public function addFilter(string $filterType, iterable $filterTypeConfig = []): FilterItem
    {
        $filterManager = $this->grid->getFilterManager();

        return $filterManager
            ->addFilter($this->getName(), $this->getLabel(), $filterType, $filterTypeConfig)
            ->setOwner($this);
    }

    /**
     * @return FilterCollection|FilterItem[]
     */
    public function getFilters(): FilterCollection
    {
        $filterManager = $this->grid->getFilterManager();

        return $filterManager->getFilters()->findByOwner($this);
    }
}
