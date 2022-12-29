<?php

namespace Database\Factories;

use Arbory\Base\Files\ArboryFile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

class ArboryFileModelFactory extends Factory
{
    protected $model = ArboryFile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $sha1 = $this->faker->sha1;

        return [
            'owner_id' => 1,
            'owner_type' => Model::class,
            'original_name' => $this->faker->text(10),
            'local_name' => $sha1,
            'disk' => 'private',
            'sha1' => $sha1,
            'size' => $this->faker->randomNumber(5),
        ];
    }
}
