<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

        \App\Models\Camper::create([
            'name' => 'Hymer B-ML',
            'description' => 'Un camper spazioso, perfetto per le vacanze in famiglia.',
            'price_per_day' => 85.00,
            'image_path' => 'campers/camper.png'
        ]);
    }
}
