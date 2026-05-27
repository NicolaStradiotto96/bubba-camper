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
            'description' => 'Il 6 posti perfetto: mansardato, doppio matrimoniale e garage per ogni avventura.',
            'full_description' => 'Camper mansardato McLouis Glamys 226 su meccanica Citroen 2.200 140 CV anno 2023. Omologato 6 posti letto e 6 posti viaggio, dotato di 2 letti matrimoniali sempre pronti, più 2 posti letto in dinette. Bagno con doccia separata e garage posteriore di grandi dimensioni.',
            'image_path' => 'campers/camper.png',
            'images' => [
                'campers/camper.png',
                'campers/camper.png',
            ],
            'seats' => 6,
            'beds' => 6,
            'is_active' => true,
        ]);
    }
}
