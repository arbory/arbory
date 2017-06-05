<?php

namespace CubeSystems\Leaf\Admin\Grid;

use CubeSystems\Leaf\Admin\Grid;
use CubeSystems\Leaf\Admin\Tools\Toolbox;
use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Row
 * @package CubeSystems\Leaf\Admin\Grid
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
     * @return Collection
     */
    public function getCells(): Collection
    {
        return $this->grid->getColumns()->map( function ( Column $column )
        {
            $cell = new Cell( $column, $this, $this->model );

            return $cell->render();
        } );
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
        return $this->getCells()->map( function( Element $cell ) {
            return strip_tags( $cell );
        } )->toArray();
    }
}
