<?php

namespace Arbory\Base\Services;

use Arbory\Base\Admin\Settings\Setting;
use Illuminate\Support\Facades\Artisan;

class NodeRoutesCache
{
    protected const CACHE_KEY = 'nodes.last_update';

    public static function cacheRoutes(): void
    {
        Artisan::call('route:cache');
        file_put_contents(self::getCachedRoutesTimestampPath(), self::getLatestNodeUpdateTimestamp());
    }

    public static function clearCache(): void
    {
        Artisan::call('route:clear');

        $cachedRoutesTimestampPath = self::getCachedRoutesTimestampPath();
        if (file_exists($cachedRoutesTimestampPath)) {
            unlink($cachedRoutesTimestampPath);
        }
    }

    public static function getCurrentCacheTimestamp(): ?int
    {
        $cachedRoutesTimestampPath = self::getCachedRoutesTimestampPath();

        if (!file_exists($cachedRoutesTimestampPath)) {
            return null;
        }

        return (int)file_get_contents($cachedRoutesTimestampPath);
    }

    // use dedicated timestamp file to fix filesystem vs laravel app timezone problems
    private static function getCachedRoutesTimestampPath(): string
    {
        return app()->getCachedRoutesPath() . '.timestamp';
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
        $lastUpdate = Setting::where('name', self::CACHE_KEY)->first();

        if (!$lastUpdate) {
            return null;
        }

        return (int)$lastUpdate->value;
    }

    public static function setLastUpdateTimestamp(int $time): void
    {
        Setting::updateOrCreate(
            ['name' => self::CACHE_KEY],
            ['value' => $time]
        );
    }
}
