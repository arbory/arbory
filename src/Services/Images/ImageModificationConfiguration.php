<?php

namespace Arbory\Base\Services\Images;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use function config;

class ImageModificationConfiguration
{
    public function getPreset(string $preset = ''): ?array
    {
        return config('arbory.glide.presets.' . $preset);
    }

    public function getOutputDisk(): Filesystem
    {
        return Storage::disk(config('arbory.glide.output_disk_name'));
    }

    public function getModifiableExtensions(): ?array
    {
        return config('arbory.glide.modifiable_image_extensions');
    }
}
