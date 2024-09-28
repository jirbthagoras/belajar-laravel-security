<?php

namespace Database\Seeders;

use App\Models\Todo;
use http\Client\Curl\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::where("email", "1@localhost")->first();

        $todo = new Todo();

        $todo->title = "test";
        $todo->description = "test";
        $todo->user_id = $user->id;

        $todo->save();
    }
}
