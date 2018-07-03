<?php

namespace Arbory\Base\Admin\Exports\Type;

use Arbory\Base\Admin\Exports\DataSetExport;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelExport implements FromCollection
{
    /**
     * @var DataSetExport
     */
    protected $items;

    /**
     * ExcelExport constructor.
     * @param DataSetExport $items
     */
    public function __construct(DataSetExport $items)
    {
        $this->items = $items;
    }

    /**
     * @return DataSetExport
     */
    public function collection()
    {
        return $this->items;
    }
}
