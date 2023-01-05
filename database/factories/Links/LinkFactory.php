<?php

namespace Database\Factories\Links;

use Arbory\Base\Links\Link;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    protected $model = Link::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word,
            'new_tab' => $this->faker->boolean,
            'href' => $this->faker->url,
        ];
    }
}
