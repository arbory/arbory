<?php

namespace Arbory\Base\Providers;

use Illuminate\Support\ServiceProvider;
use Arbory\Base\Admin\Navigator\Navigator;
use Arbory\Base\Admin\Constructor\BlockRegistry;
use Arbory\Base\Admin\Constructor\Blocks\TextBlock;

class ConstructorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(Navigator::class);
        $this->app->singleton(BlockRegistry::class, function () {
            $registry = new BlockRegistry($this->app);

            $registry->register(TextBlock::class);

            return $registry;
        });
    }
}
