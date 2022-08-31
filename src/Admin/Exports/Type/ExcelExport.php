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
    public const EXTENSION = 'xlsx';

    /**
     * ExcelExport constructor.
     */
    public function __construct(protected DataSetExport $export)
    {
    }

    public function collection(): Collection
    {
        return $this->export->getItems();
    }

    public function headings(): array
    {
        return $this->export->getColumns();
    }

    public function download(string $fileName): BinaryFileResponse
    {
        return Excel::download($this, $fileName.'.'.self::EXTENSION);
    }
}
