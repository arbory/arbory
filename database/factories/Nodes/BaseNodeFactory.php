<?php

namespace Database\Factories\Nodes;

use Arbory\Base\Nodes\Node;
use Illuminate\Database\Eloquent\Factories\Factory;

class BaseNodeFactory extends Factory
{
    protected $model = Node::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'name' => $this->faker->word,
            'slug' => $this->faker->unique()->slug,
            'item_position' => null,
            'locale' => null,
            'activate_at' => now(),
            'expire_at' => null,
        ];
    }
}
