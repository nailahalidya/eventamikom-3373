<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Utama
        User::create([
            'name'     => 'Admin Amikom',
            'email'    => 'admin@amikom.ac.id',
            'password' => Hash::make('password'), // Menggunakan Hash facade lebih disarankan
            'role'     => 'admin',
        ]);

        // 2. Insert Kategori Event
        $itCategory = Category::create([
            'name' => 'Seminar IT',
            'slug' => 'seminar-it',
        ]);

        $entertainmentCategory = Category::create([
            'name' => 'Entertainment', // Memperbaiki typo: Entertaiment -> Entertainment
            'slug' => 'entertainment',
        ]);

        // 3. Insert Sampel Events
        $events = [
            [
                'category_id' => $entertainmentCategory->id,
                'title'       => 'Jazz Night 2025',
                'description' => 'Nikmati malam yang indah dengan alunan musik jazz yang merdu.',
                'date'        => '2026-05-10 19:00:00',
                'location'    => 'Amikom Baru',
                'price'       => 50000,
                'stock'       => 100,
                'poster_path' => 'posters/event-1.png',
            ],
            [
                'category_id' => $itCategory->id,
                'title'       => 'Hackathon - Unleash Your Inner Developer',
                'description' => 'Ayo asah skill coding kamu dan ciptakan solusi inovatif untuk tantangan masa depan!',
                'date'        => '2026-05-05 10:00:00',
                'location'    => 'Inkubator Amikom',
                'price'       => 50000,
                'stock'       => 100,
                'poster_path' => 'posters/event-2.png',
            ],
            [
                'category_id' => $itCategory->id,
                'title'       => 'AI & FUTURE TECH SUMMIT 2026',
                'description' => 'Jelajahi tren terkini dalam kecerdasan buatan dan teknologi masa depan bersama para ahli di bidangnya.',
                'date'        => '2026-05-01 13:00:00',
                'location'    => 'Cinema Unit 6',
                'price'       => 50000,
                'stock'       => 100,
                'poster_path' => 'posters/event-3.png',
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
