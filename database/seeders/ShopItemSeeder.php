<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShopItem;

class ShopItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShopItem::firstOrCreate([
            'name' => 'IPhone 16',
        ], [
            'name' => 'IPhone 16',
            'cost' => 100,
        ]);
    }
}
