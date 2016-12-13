<?php

namespace CubeSystems\Leaf\Providers;

use Cartalyst\Sentinel\Laravel\SentinelServiceProvider;
use Composer\Composer;
use Illuminate\Database\Migrations\Migrator;

/**
 * Class LeafSentinelServiceProvider
 * @package CubesSystems\Leaf\Services
 */
class LeafSentinelServiceProvider extends SentinelServiceProvider
{
    /**
     *
     */
    public function register()
    {
        parent::register();
        $this->registerMigrations();
    }

    /**
     * @param string $packageName
     * @return string|null
     */
    protected function getComposerPackagePath( $packageName )
    {
        $composer = $this->app->make( Composer::class );
        /** @var $composer Composer */

        $repositoryManager = $composer->getRepositoryManager();
        $installationManager = $composer->getInstallationManager();
        $localRepository = $repositoryManager->getLocalRepository();

        $packages = $localRepository->getPackages();

        $packagePath = null;
        foreach( $packages as $package )
        {
            /* @var $package \Composer\Package\CompletePackage */

            if( $package->getName() === $packageName )
            {
                $packagePath = $installationManager->getInstallPath( $package );
                break;
            }
        }

        return $packagePath;
    }

    /**
     *
     */
    protected function prepareResources()
    {
        $sentinelPath = $this->getComposerPackagePath( 'cartalyst/sentinel' );

        // Publish config
        $config = realpath( $sentinelPath . '/src//config/config.php' );

        $this->mergeConfigFrom( $config, 'cartalyst.sentinel' );

        $this->publishes( [
            $config => config_path( 'cartalyst.sentinel.php' ),
        ], 'config' );
    }

    /**
     *
     */
    protected function registerMigrations()
    {
        $sentinelPath = $this->getComposerPackagePath( 'cartalyst/sentinel' );
        $path = realpath( $sentinelPath . '/src/migrations' );

        /**
         * @var $migrator Migrator
         */
        $migrator = $this->app->make( 'migrator' );
        $migrator->path( $path );
    }
}
