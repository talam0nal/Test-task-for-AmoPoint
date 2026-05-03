<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BalanceHistory;
use App\Models\User;

class BalanceHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::whereNotNull('tg_id')->first();
        BalanceHistory::firstOrcreate([
            'text' => 'Покупка Iphone 16',
        ], [
            "user_id" => $user->id,
            "type" => BalanceHistory::OUTPUT_TYPE,
            "operation" => BalanceHistory::PURCHASE_OPERATION,
            "sum" => 100,
            "text" => 'Покупка Iphone 16',
            "created_at" => now()->subDays(3),
        ]);

        BalanceHistory::firstOrcreate([
            'text' => 'Покупка Samsung Galaxy S24',
        ], [
            "user_id" => $user->id,
            "type" => BalanceHistory::OUTPUT_TYPE,
            "operation" => BalanceHistory::PURCHASE_OPERATION,
            "sum" => 200,
            "text" => 'Покупка Samsung Galaxy S24',
            "created_at" => now()->subDays(2),
        ]);

    }
}
