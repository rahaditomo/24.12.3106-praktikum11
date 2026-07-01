<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Partner::create(['name' => 'Cristiano Ronaldo', 'logo_url' => 'https://placehold.co/200x200']);
        \App\Models\Partner::create(['name' => 'Lionel Messi', 'logo_url' => 'https://placehold.co/200x200']);
        \App\Models\Partner::create(['name' => 'Neymar Jr.', 'logo_url' => 'https://placehold.co/200x200']);
        \App\Models\Partner::create(['name' => 'Sergio Ramos', 'logo_url' => 'https://placehold.co/200x200']);
        \App\Models\Partner::create(['name' => 'Karim Benzema', 'logo_url' => 'https://placehold.co/200x200']);
    }
}
