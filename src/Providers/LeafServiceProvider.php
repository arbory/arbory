<?php namespace CubeSystems\Leaf\Providers;

use CubeSystems\Leaf\Menu\Menu;
use Dimsav\Translatable\TranslatableServiceProvider;
use Illuminate\Support\ServiceProvider;

/**
 * Class LeafServiceProvider
 * @package CubeSystems\Leaf
 */
class LeafServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom( base_path( 'packages/CubeSystems/Leaf/resources/views' ), 'leaf' );
        $this->loadTranslationsFrom( __DIR__ . '/../../resources/lang', 'leaf' );

        $this->publishResources();
        $this->publishMigrations();

        $this->app->register( TranslatableServiceProvider::class );
        $this->app->register( LeafFileServiceProvider::class );

        include __DIR__ . '/../../routes/admin.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( 'leaf.menu', function ()
        {
            return new Menu( config( 'leaf.menu' ) );
        }, true );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array( 'Leaf' );
    }

    /**
     * Publish configuration file.
     */
    private function publishResources()
    {
        $this->publishes( [
            __DIR__ . '/../../config/leaf.php' => config_path( 'leaf.php' )
        ], 'config' );

        $this->publishes( [
            __DIR__ . '/../../resources/views/' => base_path( 'resources/views/vendor/leaf/admin' ),
        ], 'view' );
    }

    /**
     *
     * Publish migration file.
     */
    private function publishMigrations()
    {
        $this->publishes( [
            __DIR__ . '/../../database/migrations/' => base_path( 'database/migrations' )
        ], 'migrations' );

        $this->publishes( [
            __DIR__ . '/../../database/seeds/' => base_path( 'database/seeds' )
        ], 'seeds' );
    }

}
