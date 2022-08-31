<?php

namespace Arbory\Base\Admin\Grid;

use Arbory\Base\Html\Html;
use Arbory\Base\Admin\Grid;
use Illuminate\Support\Collection;
use Arbory\Base\Admin\Tools\Toolbox;
use Arbory\Base\Html\Elements\Element;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Row.
 */
class Row implements Renderable
{
    /**
     * Row constructor.
     */
    public function __construct(protected Grid $grid, protected Model $model)
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
     * @return Collection|Cell[]
     */
    public function getCells(): Collection
    {
        return $this->grid->getColumns()->map(fn(Column $column) => new Cell($column, $this, $this->model));
    }

    /**
     * @return Element
     */
    public function render()
    {
        $cells = $this->getCells();

        if ($this->grid->isToolboxEnable()) {
            $cells->push(
                Html::td(
                    Toolbox::create(
                        $this->grid->getModule()->url('dialog', ['dialog' => 'toolbox', 'id' => $this->model->getKey()])
                    )->render()
                )->addClass('only-icon toolbox-cell')
            );
        }

        return Html::tr($cells->toArray())
            ->addAttributes([
                'data-id' => $this->model->getKey(),
            ])
            ->addClass('row');
    }

    public function toArray(): array
    {
        return $this->getCells()->mapWithKeys(fn(Cell $cell) => [$cell->getColumn()->getName() => strip_tags($cell)])->toArray();
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
