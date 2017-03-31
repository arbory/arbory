<?php

use Cartalyst\Sentinel\Sentinel;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder;

class LeafDatabaseSeeder extends Seeder
{
    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    public function __construct( DatabaseManager $databaseManager )
    {
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

    protected function seedLocales()
    {
        $connection = $this->databaseManager->connection();
        $table = $connection->table( 'translator_languages' );

        if ( !$table->first() )
        {
            $table->insert( [
                'locale' => 'en',
                'name' => 'English',
            ] );
        }
    }
}
