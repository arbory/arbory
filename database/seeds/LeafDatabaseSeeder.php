<?php

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
}
