<?php

namespace Arbory\Base\Admin\Exports\Type;

use Illuminate\Support\Facades\File;
use Arbory\Base\Admin\Exports\DataSetExport;
use Arbory\Base\Admin\Exports\ExportInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class JsonExport implements ExportInterface
{
    public const EXTENSION = 'json';

    /**
     * ExcelExport constructor.
     */
    public function __construct(protected DataSetExport $export)
    {
    }

    public function download(string $fileName): BinaryFileResponse
    {
        $fileName = vsprintf('%s-%s.%s', [
            $fileName,
            time(),
            self::EXTENSION,
        ]);

        $tempPath = sys_get_temp_dir().'/'.$fileName;

        File::put($tempPath, $this->export->getItems()->toJson(JSON_PRETTY_PRINT));

        return response()->download($tempPath)->deleteFileAfterSend();
    }
}
