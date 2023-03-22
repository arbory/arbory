<?php

namespace Arbory\Base\Console\Commands;

use Arbory\Base\Services\NodeRoutesCache;
use Illuminate\Console\Command;

class RouteCacheCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'arbory:route-cache';

    /**
     * @var string
     */
    protected $description = 'Laravel route:cache wrapper with Arbory nodes update detection';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'arbory:route-cache {--json}';

    public function handle()
    {
        $jsonOutput = $this->option('json');
        $updated = false;

        if (NodeRoutesCache::isRouteCacheObsolete()) {
            NodeRoutesCache::cacheRoutes();
            $updated = true;
        }

        if ($jsonOutput) {
            $this->info(json_encode(['updated' => $updated]));
        } elseif ($updated) {
            $this->info('Obsolete route cache refreshed');
        }
    }
}
