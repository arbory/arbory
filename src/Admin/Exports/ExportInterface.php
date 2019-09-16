<?php

namespace Arbory\Base\Admin\Exports;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Interface ExportInterface.
 * @package Arbory\Base\Admin\Grid
 */
interface ExportInterface
{
    public function download(string $fileName): BinaryFileResponse;
}
