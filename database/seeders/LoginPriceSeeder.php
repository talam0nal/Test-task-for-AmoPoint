<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LoginPrice;

class LoginPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        LoginPrice::firstOrcreate([
            'days' => 7,
        ], [
            'days' => 7,
            'stars' => 100,
        ]);

        LoginPrice::firstOrcreate([
            'days' => 30,
        ], [
            'days' => 30,
            'stars' => 200,
        ]);

        LoginPrice::firstOrcreate([
            'days' => 60,
        ], [
            'days' => 60,
            'stars' => 500,
        ]);

        LoginPrice::firstOrcreate([
            'days' => 365,
        ], [
            'days' => 365,
            'stars' => 1000,
        ]);

    }
}
