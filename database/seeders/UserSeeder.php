<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()
            ->create([
                "name" => "1",
                "email" => "1@localhost",
                "password" => Hash::make("1"),
                "token" => "secret"
            ]);

        User::query()
            ->create([
                "name" => "2",
                "email" => "2@localhost",
                "password" => Hash::make("2"),
                "token" => "secret"
            ]);
    }
}
