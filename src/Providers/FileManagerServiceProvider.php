<?php

namespace Arbory\Base\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageServiceProvider;
use UniSharp\LaravelFilemanager\LaravelFilemanagerServiceProvider;

class FileManagerServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->registerServiceProviders();
        $this->registerAliases();
        $this->publishAssets();

        if (config('arbory.lfm.register_routes')) {
            Route::group([
                'prefix' => config('arbory.lfm.prefix'),
                'middleware' => config('arbory.lfm.middleware'),
            ], function () {
                \UniSharp\LaravelFilemanager\Lfm::routes();
            });
        }
    }

    /**
     * @return void
     */
    protected function registerServiceProviders(): void
    {
        $this->app->register(LaravelFilemanagerServiceProvider::class);
        $this->app->register(ImageServiceProvider::class);
    }

    /**
     * @return void
     */
    protected function registerAliases(): void
    {
        AliasLoader::getInstance()->alias('Image', \Intervention\Image\Facades\Image::class);
    }

    /**
     * @return void
     */
    protected function publishAssets(): void
    {
        $lfmVendorPath = __DIR__.'/../../../../unisharp/laravel-filemanager/';
        $fileManagerFileDest = base_path('resources/lang/vendor/laravel-filemanager');

        $this->publishes([
            $lfmVendorPath.'src/lang' => $fileManagerFileDest,
        ], 'file_manager');

        $this->publishes([
            __DIR__.'/../../config/lfm.php' => config_path('lfm.php'),
        ], 'file_manager');

        $this->loadTranslationsFrom($lfmVendorPath.'/lang', 'laravel-filemanager');
        $this->loadViewsFrom(__DIR__.'/../../resources/views/lfm/', 'laravel-filemanager');

        // Add fallback to original LFM views for other views which are not extended
        $this->app['view']->addNamespace('laravel-filemanager', [
            $lfmVendorPath.'src/views',
        ]);
    }
}
