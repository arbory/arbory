<?php

namespace Arbory\Base\Admin\Grid;

use Arbory\Base\Admin\Grid as AdminGrid;
use Arbory\Base\Admin\Exports\DataSetExport;
use Illuminate\Contracts\Support\Renderable;

class ExportBuilder implements Renderable
{
    /**
     * ExportBuilder constructor.
     */
    public function __construct(protected AdminGrid $grid)
    {
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
        $items = $this->grid->getRows()->map(fn(Row $row) => $row->toArray());

        $columns = $this->getColumns();

        return new DataSetExport($items, $columns);
    }
}
