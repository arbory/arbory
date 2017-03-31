<?php

use Illuminate\Database\Seeder;
use Waavi\Translation\Repositories\LanguageRepository;

class LeafDatabaseSeeder extends Seeder
{
    /**
     * @var LanguageRepository
     */
    protected $languageRepository;

    public function __construct(
        LanguageRepository $languageRepository
    )
    {
        $this->languageRepository = $languageRepository;
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
        if( empty( $this->languageRepository->availableLocales() ) )
        {
            $this->languageRepository->create( [
                'locale' => 'en',
                'name' => 'English'
            ] );
        }
    }
}
