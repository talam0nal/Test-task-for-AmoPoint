<?php

use Illuminate\Database\Seeder;
use Database\Seeders\CitySeeder;
use Database\Seeders\LotterySeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\QuestSeeder;
use Database\Seeders\ShopItemSeeder;
use Database\Seeders\BalanceHistorySeeder;
use Database\Seeders\PrizeSeeder;
use Database\Seeders\LoginPriceSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(CitySeeder::class);
         $this->call(LotterySeeder::class);
         $this->call(UserSeeder::class);
         $this->call(QuestSeeder::class);
         $this->call(ShopItemSeeder::class);
         $this->call(BalanceHistorySeeder::class);
         $this->call(PrizeSeeder::class);
         $this->call(LoginPriceSeeder::class);
         
    }
}

