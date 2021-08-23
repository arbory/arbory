<?php

namespace Arbory\Base\Services\Images;

use Arbory\Base\Files\ArboryImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\Glide\GlideImage;

class ImageModificationService
{
    private const MODIFIABLE_IMAGE_EXTENSIONS = ['jpeg', 'jpg', 'png', 'webp'];

    public function __construct(
        private ImageModificationConfiguration $modificationConfiguration
    ) {
    }

    /**
     * @param ArboryImage $imageModel
     * @param string $preset
     * @return string
     */
    public function modify(ArboryImage $imageModel, string $preset): string
    {
        if (! $this->isModifiable($imageModel)) {
            return $imageModel->getSourceUrl();
        }

        $pathToFile = Storage::disk($imageModel->getDisk())->path($imageModel->getLocalName());
        $image = GlideImage::create($pathToFile);

        $presetConfiguration = $this->modificationConfiguration->getPreset($preset);

        if ($presetConfiguration) {
            $image->modify($presetConfiguration);
        }

        $outputPath = $this->modificationConfiguration->getOutputDisk()->path($imageModel->getLocalName());
        $imagePath = $image->save($outputPath);

        return $this->modificationConfiguration->getOutputDisk()->url(File::basename($imagePath));
    }

    /**
     * @param ArboryImage $image
     * @return bool
     */
    private function isModifiable(ArboryImage $image): bool
    {
        return in_array($image->getExtension(), self::MODIFIABLE_IMAGE_EXTENSIONS);
    }
}