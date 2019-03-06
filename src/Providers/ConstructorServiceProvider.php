<?php


namespace Arbory\Base\Providers;


use Arbory\Base\Admin\Constructor\Blocks\DoubleTextBlock;
use Arbory\Base\Admin\Constructor\Blocks\TextBlock;
use Arbory\Base\Admin\Constructor\Registry;
use Arbory\Base\Admin\Navigator\Navigator;
use Illuminate\Support\ServiceProvider;

class ConstructorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(Navigator::class);
        $this->app->singleton(Registry::class, function () {
            return new Registry($this->app, [
                TextBlock::class,
                DoubleTextBlock::class
            ]);
        });
    }
}