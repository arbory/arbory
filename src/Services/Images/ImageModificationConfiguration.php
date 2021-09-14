<?php

namespace Arbory\Base\Services\Images;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use function config;

class ImageModificationConfiguration
{
    /**
     * @param  string  $preset
     * @return array|null
     */
    public function getPreset(string $preset = ''): ?array
    {
        return config('arbory.glide.presets.' . $preset);
    }

    /**
     * @return Filesystem
     */
    public function getOutputDisk(): Filesystem
    {
        return Storage::disk(config('arbory.glide.output_disk_name'));
    }

    /**
     * @return array|null
     */
    public function getModifiableExtensions(): ?array
    {
        return config('arbory.glide.modifiable_image_extensions');
    }
}
