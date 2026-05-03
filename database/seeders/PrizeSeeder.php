<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prize;
use App\Models\User;
use App\Models\Lottery;

class PrizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::whereNotNull('tg_id')->first();
        $lottery = Lottery::first();

        Prize::firstOrCreate([
            'name' => 'IPhone 16',
        ], [
            'name' => 'IPhone 16',
            'place' => 1,
            'lottery_id' => $lottery->id,
        ]);

        Prize::firstOrCreate([
            'name' => 'Samsung S24',
        ], [
            'name' => 'Samsung S24',
            'place' => 2,
            'lottery_id' => $lottery->id,
        ]);

    }
}
