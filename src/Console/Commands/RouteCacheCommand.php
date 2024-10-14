<?php

namespace Arbory\Base\Console\Commands;

use Arbory\Base\Services\NodeRoutesCache;
use Illuminate\Foundation\Console\RouteCacheCommand as LaravelRouteCacheCommand;

class RouteCacheCommand extends LaravelRouteCacheCommand
{
    /**
     * @var string
     */
    protected $name = 'arbory:route:cache';

    /**
     * @var string
     */
    protected $description = 'Laravel route:cache wrapper with Arbory nodes update detection';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'arbory:route:cache {--json} {--daemon}';

    public function handle()
    {
        $daemonMode = $this->option('daemon');
        $jsonOutput = $this->option('json');
        $run = true;

        while ($run) {
            $updated = false;

            if (NodeRoutesCache::isRouteCacheNeeded()) {
                $this->createCache();
                $updated = true;
            }

            if ($jsonOutput) {
                $this->info(json_encode(['updated' => $updated]));
            } elseif ($updated) {
                $this->components->info('Routes cached successfully.');
            }

            if ($daemonMode) {
                // Random sleep between 1-20 seconds
                sleep(rand(1, 20));
            } else {
                $run = false;
            }
        }
    }

    public function createCache()
    {
        $routes = $this->getFreshApplicationRoutes();

        if (count($routes) === 0) {
            return $this->components->error("Your application doesn't have any routes.");
        }

        foreach ($routes as $route) {
            $route->prepareForSerialization();
        }

        // write to temporary file
        $temporaryRoutePath = NodeRoutesCache::getCachedRoutesPath('tmp');
        $this->files->put($temporaryRoutePath, $this->buildRouteCacheFile($routes));

        // make atomic filesytem operation
        $this->files->move($temporaryRoutePath, NodeRoutesCache::getCachedRoutesPath());

        // store update timestamp file
        $this->files->put(
            NodeRoutesCache::getCachedRoutesTimestampPath(),
            NodeRoutesCache::getLatestNodeUpdateTimestamp()
        );
    }
}
