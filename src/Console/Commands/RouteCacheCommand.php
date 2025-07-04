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
                $this->callSilent('arbory:route:clear');

                $this->storeRouteCache($this->getRouteCache(), NodeRoutesCache::getLatestNodeUpdateTimestamp());
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

    protected function getRouteCache(): ?string
    {
        $routes = $this->getFreshApplicationRoutes();

        if (count($routes) === 0) {
            $this->components->error("Your application doesn't have any routes.");
            return null;
        }

        foreach ($routes as $route) {
            $route->prepareForSerialization();
        }

        return $this->buildRouteCacheFile($routes);
    }

    protected function storeRouteCache(?string $routeCache, ?int $timestamp): void
    {
        if ($routeCache === null) {
            return;
        }

        // write to temporary file
        $temporaryRoutePath = NodeRoutesCache::getCachedRoutesPath('tmp');
        $this->files->put($temporaryRoutePath, $routeCache);

        // make atomic filesytem operation
        $this->files->move($temporaryRoutePath, NodeRoutesCache::getCachedRoutesPath());

        // store update timestamp file
        $this->files->put(NodeRoutesCache::getCachedRoutesTimestampPath(), $timestamp);
    }
}
