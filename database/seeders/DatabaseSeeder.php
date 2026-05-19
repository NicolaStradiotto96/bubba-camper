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
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '+393331234567',
            'is_admin' => false,
        ]);

        User::factory()->create([
            'first_name' => 'Stefano',
            'last_name' => 'Test',
            'email' => 'info@bubbacamper.com',
            'phone' => '+393331234567',
            'is_admin' => true,
        ]);

        Camper::create([
            'name' => 'McLouis Glamys 226',
            'slug' => Str::slug('McLouis Glamys 226'),
            'description' => 'Il 6 posti perfetto: mansardato, doppio matrimoniale, doccia XL e garage per ogni avventura.',
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
