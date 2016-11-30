<?php


namespace CubeSystems\Leaf\Providers;

use CubeSystems\Leaf\Files\LeafFile;
use CubeSystems\Leaf\Repositories\LeafFilesRepository;
use Illuminate\Support\ServiceProvider;

class LeafFileServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton( 'leaf_files', function ()
        {
            return new LeafFilesRepository( 'local', LeafFile::class );
        } );
    }
}