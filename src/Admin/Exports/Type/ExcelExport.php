<?php

namespace Arbory\Base\Admin\Exports\Type;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Arbory\Base\Admin\Exports\DataSetExport;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Arbory\Base\Admin\Exports\ExportInterface;
use Maatwebsite\Excel\Concerns\FromCollection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelExport implements FromCollection, WithHeadings, ExportInterface
{
    const EXTENSION = 'xlsx';

    /**
     * @var DataSetExport
     */
    protected $export;

    /**
     * ExcelExport constructor.
     * @param DataSetExport $export
     */
    public function __construct(DataSetExport $export)
    {
        $this->export = $export;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(): Collection
    {
        return $this->export->getItems();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->export->getColumns();
    }

    /**
     * @param string $fileName
     * @return BinaryFileResponse
     */
    public function download(string $fileName): BinaryFileResponse
    {
        return Excel::download($this, $fileName.'.'.self::EXTENSION);
    }
}
