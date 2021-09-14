<?php

namespace Arbory\Base\Services\Images;

use Arbory\Base\Files\ArboryImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\Glide\GlideImage;

class ImageModificationService
{
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
        if (!Storage::disk($imageModel->getDisk())->exists($imageModel->getLocalName())) {
            return '';
        }

        if (!$this->isModifiable($imageModel)) {
            return $imageModel->getSourceUrl();
        }

        if ($presetConfiguration = $this->modificationConfiguration->getPreset($preset)) {
            $localImageName = $this->getModifiedImageName($preset, $imageModel);
            return $this->getModifiedImageUrl($imageModel, $presetConfiguration, $localImageName);
        }

        return $imageModel->getSourceUrl();
    }

    /**
     * @param ArboryImage $image
     * @return bool
     */
    private function isModifiable(ArboryImage $image): bool
    {
        return in_array($image->getExtension(), $this->modificationConfiguration->getModifiableExtensions());
    }

    /**
     * @param string $presetName
     * @param ArboryImage $imageModel
     * @return string
     */
    private function getModifiedImageName(string $presetName, ArboryImage $imageModel): string
    {
        $name = File::name($imageModel->getLocalName());
        return $name .'/'. $name . '_' . $presetName . '.' . File::extension($imageModel);
    }


    /**
     * @param ArboryImage $imageModel
     * @param array $presetConfiguration
     * @param string $localImageName
     * @return string
     */
    public function getModifiedImageUrl(
        ArboryImage $imageModel,
        array $presetConfiguration,
        string $localImageName
    ): string {
        if (!$this->modificationConfiguration->getOutputDisk()->exists($localImageName)) {
            $this->createModifiedImage($imageModel, $presetConfiguration, $localImageName);
        }
        return $this->modificationConfiguration->getOutputDisk()->url($localImageName);
    }

    /**
     * @param ArboryImage $imageModel
     * @param string $localImageName
     * @return string
     */
    private function getModifiedImageOutputPath(ArboryImage $imageModel, string $localImageName): string
    {
        $outputDisk = $this->modificationConfiguration->getOutputDisk();
        $outputDisk->makeDirectory(File::name($imageModel->getLocalName()));
        return $outputDisk->path($localImageName);
    }

    /**
     * @param ArboryImage $imageModel
     * @param array $presetConfiguration
     * @param string $localImageName
     */
    private function createModifiedImage(ArboryImage $imageModel, array $presetConfiguration, string $localImageName)
    {
        $pathToFile = Storage::disk($imageModel->getDisk())->path($imageModel->getLocalName());
        $image = GlideImage::create($pathToFile);
        $image->modify($presetConfiguration);
        $image->save($this->getModifiedImageOutputPath($imageModel, $localImageName));
    }
}
