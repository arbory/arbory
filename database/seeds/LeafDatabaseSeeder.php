<?php

use CubeSystems\Leaf\Http\Controllers\Admin\MenuBuilderController;
use CubeSystems\Leaf\Http\Controllers\Admin\NodesController;
use CubeSystems\Leaf\Http\Controllers\Admin\RolesController;
use CubeSystems\Leaf\Http\Controllers\Admin\TranslationsController;
use CubeSystems\Leaf\Http\Controllers\Admin\UsersController;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder;
use Waavi\Translation\Repositories\LanguageRepository;

class LeafDatabaseSeeder extends Seeder
{
    /**
     * @var LanguageRepository
     */
    protected $languageRepository;

    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    public function __construct(
        LanguageRepository $languageRepository,
        DatabaseManager $databaseManager
    )
    {
        $this->languageRepository = $languageRepository;
        $this->databaseManager = $databaseManager;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedLocales();
        $this->seedMenuItems();
    }

    /**
     * @return void
     */
    protected function seedLocales()
    {
        if( $this->languageRepository->getModel()->all()->isEmpty() )
        {
            $this->languageRepository->create( [
                'locale' => 'en',
                'name' => 'English'
            ] );
        }
    }

    /**
     * @return void
     */
    protected function seedMenuItems()
    {
        $connection = $this->databaseManager->connection();
        $table = $connection->table( 'admin_menu_items' );

        if ( !$table->first() )
        {
            $table->insert( [
                'title' => 'Nodes',
                'module' => NodesController::class,
                'order' => 1
            ] );

            $table->insert( [
                'title' => 'Menu',
                'module' => MenuBuilderController::class,
                'order' => 2
            ] );

            $table->insert( [
                'title' => 'Users',
                'order' => 3
            ] );

            $usersMenuItemId = $connection->getPdo()->lastInsertId();

            $table->insert( [
                'title' => 'Admin users',
                'parent' => $usersMenuItemId,
                'module' => UsersController::class,
                'order' => 0
            ] );

            $table->insert( [
                'title' => 'Admin roles',
                'parent' => $usersMenuItemId,
                'module' => RolesController::class,
                'order' => 1
            ] );

            $table->insert( [
                'title' => 'Translations',
                'module' => TranslationsController::class,
                'order' => 4
            ] );
        }
    }
}
