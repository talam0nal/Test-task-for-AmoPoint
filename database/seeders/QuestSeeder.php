<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quest;
use App\Models\City;

class QuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $city = City::first();
        Quest::firstOrCreate([
            'name' => 'Авторизация каждые 7 дней',
        ], [
            'name' => 'Авторизация каждые 7 дней',
            'description' => 'Заходи каждый день и получай награду после 7 дней. Если пропустить - прогресс обнулится',
            'city_id' => $city->id,
            'stars' => 100,
            'expired_at' => now()->addMonths(3),
            'type' => Quest::ACTIVITY_TYPE,
        ]);
    }
}
