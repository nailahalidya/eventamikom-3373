<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Event;
use App\Models\Partner;
use App\Models\Organizer;
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
        $admin = User::firstOrCreate(
            ['email' => 'admin@amikom.ac.id'],
            [
                'name'     => 'Admin Amikom',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // 2. Akun Organizer Sampel
        $organizerUser = User::firstOrCreate(
            ['email' => 'koma@amikom.ac.id'],
            [
                'name'     => 'Komunitas Multimedia Amikom',
                'password' => Hash::make('password'),
                'role'     => 'organizer',
            ]
        );

        $organizer = Organizer::firstOrCreate(
            ['user_id' => $organizerUser->id],
            [
                'name'        => 'KOMA Amikom',
                'email'       => 'koma@amikom.ac.id',
                'phone'       => '081234567890',
                'description' => 'Komunitas Multimedia Universitas Amikom Yogyakarta yang berfokus pada pengembangan animasi, desain, dan multimedia.',
                'status'      => 'approved',
                'logo'        => 'https://ui-avatars.com/api/?name=KOMA+Amikom&background=4f46e5&color=fff',
            ]
        );

        // 3. Insert Partner (Sponsor / Pendukung Platform)
        $partners = [
            [
                'name'     => 'Amikom Computer Club (AMCC)',
                'logo_url' => 'https://ui-avatars.com/api/?name=AMCC&background=0284c7&color=fff',
            ],
            [
                'name'     => 'Komunitas Multimedia Amikom (KOMA)',
                'logo_url' => 'https://ui-avatars.com/api/?name=KOMA&background=4f46e5&color=fff',
            ],
            [
                'name'     => 'BEM Universitas Amikom',
                'logo_url' => 'https://ui-avatars.com/api/?name=BEM+Amikom&background=dc2626&color=fff',
            ],
            [
                'name'     => 'GDSC Amikom Yogyakarta',
                'logo_url' => 'https://ui-avatars.com/api/?name=GDSC+Amikom&background=16a34a&color=fff',
            ],
        ];

        foreach ($partners as $p) {
            Partner::firstOrCreate(['name' => $p['name']], $p);
        }

        // 4. Insert Kategori Event
        $itCategory = Category::firstOrCreate(['slug' => 'seminar-it'], [
            'name' => 'Seminar IT',
            'slug' => 'seminar-it',
        ]);

        $entertainmentCategory = Category::firstOrCreate(['slug' => 'entertainment'], [
            'name' => 'Entertainment',
            'slug' => 'entertainment',
        ]);

        $workshopCategory = Category::firstOrCreate(['slug' => 'workshop-tech'], [
            'name' => 'Workshop & Tech',
            'slug' => 'workshop-tech',
        ]);

        $competitionCategory = Category::firstOrCreate(['slug' => 'kompetisi'], [
            'name' => 'Kompetisi & Hackathon',
            'slug' => 'kompetisi',
        ]);

        // 5. Insert Sampel Events
        $events = [
            [
                'category_id' => $entertainmentCategory->id,
                'owner_type'  => 'admin',
                'title'       => 'Jazz Night 2025',
                'description' => 'Nikmati malam yang indah dengan alunan musik jazz yang merdu di Kampus Amikom Yogyakarta.',
                'date'        => '2026-05-10 19:00:00',
                'location'    => 'Amikom Baru',
                'price'       => 50000,
                'stock'       => 100,
                'poster_path' => 'posters/event-1.png',
            ],
            [
                'category_id' => $competitionCategory->id,
                'owner_type'  => 'organizer',
                'organizer_id'=> $organizer->id,
                'title'       => 'Hackathon - Unleash Your Inner Developer',
                'description' => 'Ayo asah skill coding kamu dan ciptakan solusi inovatif untuk tantangan masa depan bersama developer se-DIY!',
                'date'        => '2026-05-05 10:00:00',
                'location'    => 'Inkubator Amikom',
                'price'       => 50000,
                'stock'       => 100,
                'poster_path' => 'posters/event-2.png',
            ],
            [
                'category_id' => $itCategory->id,
                'owner_type'  => 'admin',
                'title'       => 'AI & FUTURE TECH SUMMIT 2026',
                'description' => 'Jelajahi tren terkini dalam kecerdasan buatan dan teknologi masa depan bersama para ahli di bidangnya.',
                'date'        => '2026-05-01 13:00:00',
                'location'    => 'Cinema Unit 6',
                'price'       => 50000,
                'stock'       => 100,
                'poster_path' => 'posters/event-3.png',
            ],
        ];

        foreach ($events as $eventData) {
            Event::firstOrCreate(['title' => $eventData['title']], $eventData);
        }
    }
}
