<?php

namespace Arbory\Base\Services;

use Illuminate\Routing\RouteCollection;
use Arbory\Base\Admin\Form\Fields\Text;
use Arbory\Base\Admin\Settings\Setting;
use Illuminate\Container\Container;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;

class NodeRoutesCache
{
    protected const CACHE_KEY = 'nodes.last_update';
    protected const TIMESTAMP_POSTFIX = 'timestamp';

    public static function getCurrentCacheTimestamp(): ?int
    {
        $cachedRoutesTimestampPath = self::getCachedRoutesTimestampPath();

        if (!file_exists($cachedRoutesTimestampPath)) {
            return null;
        }

        return (int)file_get_contents($cachedRoutesTimestampPath);
    }

    // use dedicated timestamp file to fix filesystem vs laravel app timezone problems
    public static function getCachedRoutesTimestampPath(): string
    {
        return self::getCachedRoutesPath(self::TIMESTAMP_POSTFIX);
    }

    public static function getCachedRoutesPath(?string $postfix = null): string
    {
        $cachedRoutesPath = app()->getCachedRoutesPath();

        // support for symlinked route cache, so bootstrap/cache can be read-only
        if (is_link($cachedRoutesPath)) {
            $cachedRoutesPath = readlink($cachedRoutesPath);
        }

        if ($postfix) {
            $cachedRoutesPath .= '.' . $postfix;
        }

        return $cachedRoutesPath;
    }

    public static function isRouteCacheNeeded(): bool
    {
        if (!file_exists(self::getCachedRoutesPath())) {
            return true;
        }

        return self::isRouteCacheObsolete();
    }

    public static function isRouteCacheObsolete(): bool
    {
        $currentCacheTimestamp = self::getCurrentCacheTimestamp();

        if (!$currentCacheTimestamp) {
            return true;
        }

        $lastModifiedTimestamp = self::getLatestNodeUpdateTimestamp();

        if (!$lastModifiedTimestamp) {
            return false;
        }

        return $lastModifiedTimestamp > $currentCacheTimestamp;
    }

    public static function getLatestNodeUpdateTimestamp(): ?int
    {
        $lastUpdate = Setting::query()->where('name', self::CACHE_KEY)->first();

        if (!$lastUpdate) {
            return null;
        }

        return (int)$lastUpdate->value;
    }

    public static function setLastUpdateTimestamp(int $time): void
    {
        Setting::query()->updateOrCreate(
            ['name' => self::CACHE_KEY],
            ['value' => $time, 'type' => Text::class]
        );
    }
}
