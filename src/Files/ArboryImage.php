<?php

namespace Arbory\Base\Files;

use Arbory\Base\Services\Images\ImageModificationService;

/**
 * Class ArboryImage.
 */
class ArboryImage extends ArboryFile
{
    /**
     * @return string
     */
    public function getTable()
    {
        return (new parent)->getTable();
    }

    public function getUrl(string $preset = ''): string
    {
        /** @var ImageModificationService $modificationService */
        $modificationService = app(ImageModificationService::class);

        return $modificationService->modify($this, $preset);
    }

    public function getSourceUrl(): string
    {
        return parent::getUrl();
    }
}
