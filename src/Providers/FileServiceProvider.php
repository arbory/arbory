<?php

namespace Arbory\Base\Providers;

use Arbory\Base\Files\ArboryFile;
use Illuminate\Support\ServiceProvider;
use Arbory\Base\Repositories\ArboryFilesRepository;

class FileServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('arbory_files', function () {
            return new ArboryFilesRepository('local', ArboryFile::class);
        });
    }
}
