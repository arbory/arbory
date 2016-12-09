<?php namespace CubeSystems\Leaf\Providers;

use CubeSystems\Leaf\Menu\Menu;
use Dimsav\Translatable\TranslatableServiceProvider;
use Illuminate\Database\Migrations\Migrator;
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
        $this->loadViewsFrom( base_path( 'vendor/CubeSystems/Leaf/resources/views' ), 'leaf' );
        $this->loadTranslationsFrom( __DIR__ . '/../../resources/lang', 'leaf' );

        $this->publishResources();
        $this->registerMigrations();

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
            __DIR__ . '/../../gulpfile.js' => base_path( 'gulpfile.leaf.js' ),
        ], 'assets' );

        $this->publishes( [
            __DIR__ . '/../../database/seeds/' => base_path( 'database/seeds' )
        ], 'seeds' );
    }

    /**
     *
     * Publish migration file.
     */
    private function registerMigrations()
    {
        /**
         * @var $migrator Migrator
         */
        $migrator = $this->app->make('migrator');
        $migrator->path( __DIR__ . '/../../database/migrations' );
    }

}
