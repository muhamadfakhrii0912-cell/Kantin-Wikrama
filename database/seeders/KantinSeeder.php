<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Canteen;
use App\Models\Stand;
use App\Models\Category;
use App\Models\Menu;

class KantinSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Canteens
        $kantinUtama = Canteen::create(['name' => 'Kantin Utama', 'slug' => 'kantin-utama', 'description' => 'Kantin utama di gedung depan.']);
        $kantinHotel = Canteen::create(['name' => 'Kantin Hotel', 'slug' => 'kantin-hotel', 'description' => 'Kantin di area perhotelan.']);

        // 2. Stands
        $stanA = Stand::create(['canteen_id' => $kantinUtama->id, 'name' => 'Stan Soto & Bakso', 'description' => 'Menjual soto dan bakso segar.']);
        $stanB = Stand::create(['canteen_id' => $kantinUtama->id, 'name' => 'Stan Nasi Campur', 'description' => 'Prasmanan nasi campur.']);
        $stanC = Stand::create(['canteen_id' => $kantinUtama->id, 'name' => 'Stan Minuman', 'description' => 'Es teh, jus, dan kopi.']);

        // 3. Categories
        $katMakanan = Category::create(['name' => 'Makanan Berat']);
        $katJajanan = Category::create(['name' => 'Jajanan']);
        $katMinuman = Category::create(['name' => 'Minuman']);

        // 4. Menus
        Menu::create([
            'stand_id' => $stanA->id,
            'category_id' => $katMakanan->id,
            'name' => 'Soto Ayam',
            'description' => 'Soto ayam kuah bening plus nasi.',
            'price' => 12000,
            'daily_quota' => 50,
            'estimated_minutes' => 5,
        ]);

        Menu::create([
            'stand_id' => $stanA->id,
            'category_id' => $katMakanan->id,
            'name' => 'Bakso Sapi',
            'description' => 'Bakso sapi asli dengan mi kuning dan bihun.',
            'price' => 10000,
            'daily_quota' => 50,
            'estimated_minutes' => 5,
        ]);

        Menu::create([
            'stand_id' => $stanC->id,
            'category_id' => $katMinuman->id,
            'name' => 'Es Teh Manis',
            'description' => 'Es teh manis segar pelepas dahaga.',
            'price' => 3000,
            'daily_quota' => 100,
            'estimated_minutes' => 2,
        ]);
        
        Menu::create([
            'stand_id' => $stanC->id,
            'category_id' => $katMinuman->id,
            'name' => 'Es Jeruk',
            'description' => 'Es jeruk peras asli.',
            'price' => 5000,
            'daily_quota' => 50,
            'estimated_minutes' => 3,
        ]);
    }
}
