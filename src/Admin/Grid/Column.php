<?php

namespace Arbory\Base\Admin\Grid;

use Arbory\Base\Admin\Filter\FilterCollection;
use Arbory\Base\Admin\Filter\FilterItem;
use Arbory\Base\Admin\Grid;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Closure;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class Column.
 */
class Column
{
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
    protected bool $sortable = false;

    /**
     * @var bool
     */
    protected bool $searchable = true;

    /**
     * @var bool
     */
    protected bool $hasFilter = false;

    /**
     * @var
     */
    protected $filterType;

    /**
     * @var bool
     */
    protected bool $checkable = false;

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
     */
    public function __construct(protected ?string $name = null, protected ?string $label = null)
    {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getName();
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

    public function getGrid(): Grid
    {
        return $this->grid;
    }

    /**
     * @return Column
     */
    public function setGrid(Grid $grid)
    {
        $this->grid = $grid;

        return $this;
    }

    /**
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
    public function sortable(bool $isSortable = true)
    {
        $this->sortable = $isSortable;

        return $this;
    }

    /**
     * @param bool $isCheckable
     * @return $this
     */
    public function checkable(bool $isCheckable = true)
    {
        $this->checkable = $isCheckable;

        return $this;
    }

    /**
     * @param bool $isSearchable
     * @return Column
     */
    public function searchable(bool $isSearchable = true)
    {
        $this->searchable = $isSearchable;

        return $this;
    }

    /**
     * @param string|null $type
     * @return $this
     */
    public function setFilter(string $type = null)
    {
        $this->filterType = $type;
        $this->hasFilter = $type !== null;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->sortable && empty($this->relationName);
    }

    /**
     * @return bool
     */
    public function isCheckable(): bool
    {
        return $this->checkable;
    }

    /**
     * @return bool
     */
    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    /**
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
     * @return Element
     */
    public function callDisplayCallback(Model $model)
    {
        $value = $this->getValue($model);

        if ($this->displayer === null) {
            $value = (string)$value;

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
     * @return $this
     */
    public function setExportColumnDisplay(Closure $closure): self
    {
        $this->exportColumnDisplay = $closure;

        return $this;
    }

    /**
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
