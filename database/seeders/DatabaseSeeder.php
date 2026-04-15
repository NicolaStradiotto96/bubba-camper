<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Stefano',
            'email' => 'stefano@bubbacamper.it',
            'is_admin' => true,
        ]);

        \App\Models\Camper::create([
            'name' => 'Hymer B-ML',
            'slug' => Str::slug('Hymer B-ML'),
            'description' => 'Un camper spazioso, perfetto per le vacanze in famiglia.',
            'price_per_day' => 85.00,
            'image_path' => 'campers/camper.png',
            'images' => [
                'campers/camper.png',
                'campers/camper.png',
                'campers/camper.png',
                'campers/camper.png',
                'campers/camper.png',
            ],
        ]);
    }
}
