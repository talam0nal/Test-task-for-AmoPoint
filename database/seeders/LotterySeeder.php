<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lottery;
use App\Models\City;

class LotterySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $city = City::first();
        Lottery::firstOrCreate([
            'name' => 'Розыгрыш iPhone 16',
        ], [
            'name' => 'Розыгрыш iPhone 16',
            'description' => 'Победителя определяем через неделю, при помощи «Рандомайзера»',
            'cost' => 100,
            'expired_at' => now()->addMonths(3),
            'city_id' => $city->id,
        ]);
    }
}
