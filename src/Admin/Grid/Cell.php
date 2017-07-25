<?php

namespace Arbory\Base\Admin\Grid;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cell
 * @package Arbory\Base\Admin\Grid
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
    public function __construct( Column $column, Row $row, Model $model )
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
     * @return Element
     */
    public function render()
    {
        return Html::td( $this->column->callDisplayCallback( $this->model ) );
    }

}
