<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GenreSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        $genres = [
            'Rock', 'Pop', 'Hip Hop', 'R&B', 'Jazz', 
            'Classical', 'Electronic', 'Country', 'Reggae', 'Metal'
        ];

        foreach ($genres as $genre) {
            Genre::create([
                'name' => $genre,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}