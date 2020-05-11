<?php

namespace Arbory\Base\Admin\Exports;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Interface ExportInterface.
 */
interface ExportInterface
{
    public function download(string $fileName): BinaryFileResponse;
}
