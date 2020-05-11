<?php

namespace Arbory\Base\Admin\Grid;

use Arbory\Base\Admin\Grid as AdminGrid;
use Arbory\Base\Admin\Exports\DataSetExport;
use Illuminate\Contracts\Support\Renderable;

class ExportBuilder implements Renderable
{
    /**
     * @var AdminGrid
     */
    protected $grid;

    /**
     * ExportBuilder constructor.
     * @param AdminGrid $grid
     */
    public function __construct(AdminGrid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * @return array
     */
    private function getColumns()
    {
        $columns = [];

        foreach ($this->grid->getColumns() as $column) {
            $columns[] = $column->getLabel();
        }

        return $columns;
    }

    /**
     * @return DataSetExport
     */
    public function render()
    {
        $items = $this->grid->getRows()->map(function (Row $row) {
            return $row->toArray();
        });

        $columns = $this->getColumns();

        return new DataSetExport($items, $columns);
    }
}
