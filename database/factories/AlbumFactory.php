<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlbumFactory extends Factory
{
    protected $model = Album::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'artist' => $this->faker->name,
            'release_year' => $this->faker->year,
            'price' => $this->faker->randomFloat(2, 5, 50),
            'stock' => $this->faker->numberBetween(0, 100),
            'creator_by' => function () {
                return \App\Models\User::factory()->create()->id;
            },
        ];
    }
}