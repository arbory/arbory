<?php

namespace Arbory\Base\Console\Commands;

use Arbory\Base\Admin\Settings\Setting;
use Arbory\Base\Services\NodeRoutesCache;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RefreshObsoleteRouteCache extends Command
{
    /**
     * @var string
     */
    protected $name = 'arbory:refresh-obsolete-route-cache';

    /**
     * @var string
     */
    protected $description = 'Refresh obsolete route cache';

    public function handle()
    {
        if (NodeRoutesCache::isRouteCacheObsolete()) {
            NodeRoutesCache::cacheRoutes();
            $this->info("Obsolete route cache refreshed");
        }
    }
}
