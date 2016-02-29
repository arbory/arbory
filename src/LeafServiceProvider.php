<?php namespace Cubesystems\Leaf;

use CubeSystems\Leaf\Menu\Menu;
use Illuminate\Support\ServiceProvider;

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
//        $this->loadViewsFrom( base_path( 'resources/views/vendor/leaf/admin' ), 'leaf' );
        $this->loadTranslationsFrom( __DIR__ . '/../resources/lang', 'leaf' );

        $this->publishResources();
        $this->publishMigrations();

        include __DIR__ . '/Http/routes.php';
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
            __DIR__ . '/../config/leaf.php' => config_path( 'leaf.php' )
        ], 'config' );

        $this->publishes( [
            __DIR__ . '/../resources/views/' => base_path( 'resources/views/vendor/leaf/admin' ),
        ], 'view' );
    }

    /**
     *
     * Publish migration file.
     */
    private function publishMigrations()
    {
//        $this->publishes( [ __DIR__ . '/../database/migrations/' => base_path( 'database/migrations' ) ], 'migrations' );
//        $this->publishes( [ __DIR__ . '/../database/seeds/' => base_path( 'database/seeds' ) ], 'seeds' );
    }

}
