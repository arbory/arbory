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

        $langSourcePath = base_path('vendor/unisharp/laravel-filemanager/src/lang');
        $langDestPath = base_path('resources/lang/vendor/laravel-filemanager');

        $this->publishes([$langSourcePath => $langDestPath], 'lfm_lang');

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
}
