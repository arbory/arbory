<?php

namespace Arbory\Base\Admin\Exports\Type;

use Arbory\Base\Admin\Exports\DataSetExport;
use Arbory\Base\Admin\Exports\ExportInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelExport implements FromCollection, WithHeadings, ExportInterface
{
    const EXTENSION = 'xlsx';

    public function __construct(
        protected DataSetExport $export,
    ) {
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
        return Excel::download($this, $fileName . '.' . self::EXTENSION);
    }
}
