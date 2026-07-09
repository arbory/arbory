<?php

namespace Arbory\Base\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use UniSharp\LaravelFilemanager\LaravelFilemanagerServiceProvider;

class FileManagerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerServiceProviders();

        $langSourcePath = base_path('vendor/unisharp/laravel-filemanager/src/lang');
        $langDestPath = base_path('lang/vendor/laravel-filemanager');

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

    protected function registerServiceProviders(): void
    {
        $this->app->register(LaravelFilemanagerServiceProvider::class);
    }
}
