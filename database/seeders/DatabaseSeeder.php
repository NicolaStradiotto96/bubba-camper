<?php

namespace Database\Seeders;

use App\Models\Camper;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '+393331234567',
            'is_admin' => false,
        ]);

        User::firstOrCreate(
            ['email' => config('app.admin_email')],
            [
                'first_name' => 'Stefano',
                'last_name' => 'Stradiotto',
                'phone' => '+393331234567',
                'is_admin' => true,
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            ]
        );

        if (Camper::count() === 0) {
            Camper::create([
                'name' => 'McLouis Glamys 226',
                'slug' => Str::slug('McLouis Glamys 226'),
                'description' => 'Il 6 posti perfetto: mansardato, doppio matrimoniale e garage di grande dimensioni.',
                'prices' => [
                    'low' => 100,
                    'mid' => 120,
                    'high' => 140,
                ],
                'image_path' => 'campers/1.webp',
                'images' => [
                    'campers/1.webp',
                    'campers/2.webp',
                    'campers/3.webp',
                ],
            ]);
        }
    }
}
