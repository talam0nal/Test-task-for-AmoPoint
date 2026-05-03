<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\City;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::firstOrCreate([
            'email' => 'admin@velgir.com',
        ], [
            'name' => 'admin',
            'surname' => 'admin',
            'email' => 'admin@velgir.com',
            'password' => bcrypt('adminadmin'),
            'is_admin' => 1,
        ]);

    }
}
