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
     * @var Column
     */
    protected $column;

    /**
     * @var Row
     */
    protected $row;

    /**
     * @var Model
     */
    protected $model;

    /**
     * Cell constructor.
     * @param Column $column
     * @param Row $row
     * @param Model $model
     */
    public function __construct(Column $column, Row $row, Model $model)
    {
        $this->column = $column;
        $this->row = $row;
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function __toString()
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

    /**
     * @return Element
     */
    public function render(): Element
    {
        return Html::td($this->getColumnDisplay());
    }

    /**
     * @return Element
     */
    protected function getColumnDisplay(): Element
    {
        $model = $this->getModel();
        $column = $this->getColumn();

        if ($this->getRow()->getGrid()->isExportEnabled()) {
            return $column->getExportColumnDisplay($model);
        }

        if ($column->isInlineEditable()) {
            return $column->inlineEditDisplay($model);
        }

        return $column->callDisplayCallback($model);
    }
}
