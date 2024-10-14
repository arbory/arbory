<?php

namespace Arbory\Base\Console\Commands;

use Arbory\Base\Services\NodeRoutesCache;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\RouteClearCommand as LaravelRouteClearCommand;


class RouteClearCommand extends LaravelRouteClearCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'arbory:route:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the route cache file and timestamp';


    public function handle()
    {
        $this->files->delete(NodeRoutesCache::getCachedRoutesPath());
        $this->files->delete(NodeRoutesCache::getCachedRoutesTimestampPath());
        $this->components->info('Route cache cleared successfully.');
    }
}
