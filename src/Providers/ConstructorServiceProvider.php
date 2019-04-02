<?php


namespace Arbory\Base\Providers;


use Arbory\Base\Admin\Constructor\Blocks\DoubleTextBlock;
use Arbory\Base\Admin\Constructor\Blocks\TextBlock;
use Arbory\Base\Admin\Constructor\BlockRegistry;
use Arbory\Base\Admin\Navigator\Navigator;
use Illuminate\Support\ServiceProvider;

class ConstructorServiceProvider extends ServiceProvider
{
    /**
     * 
     */
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