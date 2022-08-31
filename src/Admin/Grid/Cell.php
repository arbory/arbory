<?php

namespace Arbory\Base\Admin\Grid;

use Arbory\Base\Html\Html;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Cell.
 */
class Cell implements Renderable
{
    /**
     * Cell constructor.
     */
    public function __construct(protected Column $column, protected Row $row, protected Model $model)
    {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->render();
    }

    /**
     * @return Column
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return Row
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    public function render(): Element
    {
        $grid = $this->row->getGrid();
        $model = $this->getModel();
        $column = $this->getColumn();

        $value = $grid->isExportEnabled() ?
            $column->getExportColumnDisplay($model) :
            $column->callDisplayCallback($model);

        return Html::td($value);
    }
}
