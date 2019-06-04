<?php

namespace Arbory\Base\Providers;

use Illuminate\Foundation\AliasLoader;
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
        $fileManagerFileSource = __DIR__.'/../../../../unisharp/laravel-filemanager/src/lang';
        $fileManagerFileDest = base_path('resources/lang/vendor/laravel-filemanager');

        $this->publishes([
            $fileManagerFileSource => $fileManagerFileDest,
        ], 'file_manager');

        $this->publishes([
            __DIR__.'/../../config/lfm.php' => config_path('lfm.php'),
        ], 'file_manager');

        $this->registerServiceProviders();
        $this->registerAliases();
    }

    /**
     * @return void
     */
    protected function registerServiceProviders()
    {
        $this->app->register(LaravelFilemanagerServiceProvider::class);
        $this->app->register(ImageServiceProvider::class);
    }

    /**
     * @return void
     */
    protected function registerAliases()
    {
        AliasLoader::getInstance()->alias('Image', \Intervention\Image\Facades\Image::class);
    }
}
