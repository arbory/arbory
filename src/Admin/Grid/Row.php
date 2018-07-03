<?php

namespace Arbory\Base\Admin\Grid;

use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Tools\Toolbox;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Row
 * @package Arbory\Base\Admin\Grid
 */
class Row implements Renderable
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var Model
     */
    protected $model;

    /**
     * Row constructor.
     * @param Grid $grid
     * @param Model $model
     */
    public function __construct( Grid $grid, Model $model )
    {
        $this->grid = $grid;
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
     * @return Collection|Cell[]
     */
    public function getCells(): Collection
    {
        return $this->grid->getColumns()->map(function (Column $column) {
            return new Cell($column, $this, $this->model);
        });
    }

    /**
     * @return Element
     */
    public function render()
    {
        $cells = $this->getCells();

        $cells->push(
            Html::td(
                Toolbox::create(
                    $this->grid->getModule()->url( 'dialog', [ 'dialog' => 'toolbox', 'id' => $this->model->getKey() ] )
                )->render()
            )->addClass( 'only-icon toolbox-cell' )
        );

        return Html::tr( $cells->toArray() )
            ->addAttributes( [
                'data-id' => $this->model->getKey(),
            ] )
            ->addClass( 'row' );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->getCells()->mapWithKeys( function( Cell $cell ) {
            return [ $cell->getColumn()->getName() => strip_tags( $cell ) ];
        } )->toArray();
    }

    /**
     * @return Grid
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }
}
