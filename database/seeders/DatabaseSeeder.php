<?php

namespace Database\Seeders;

use App\Models\Camper;
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
            'phone' => '3331234567',
            'is_admin' => false,
        ]);

        User::factory()->create([
            'name' => 'Stefano',
            'email' => 'stefano@bubbacamper.it',
            'phone' => '3339876543',
            'is_admin' => true,
        ]);

        Camper::create([
            'name' => 'Bubba Adventure Van',
            'slug' => Str::slug('Bubba Adventure Van'),
            'description' => 'Compatto, agile e pronto per l\'avventura. Perfetto per coppie che amano esplorare posti meno accessibili.',
            'image_path' => 'campers/camper.png',
            'images' => [
                'campers/camper.png',
                'campers/camper.png',
            ],
            'seats' => 2,
            'beds' => 2,
            'is_active' => true,
        ]);
    }
}
