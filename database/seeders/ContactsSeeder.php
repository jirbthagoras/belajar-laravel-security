<?php

namespace Database\Seeders;

use App\Models\Contact;
use http\Client\Curl\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::query()
            ->where("name", "=", "1")->firstOrFail();

        $contact = new Contact();

        $contact->name = "test";
        $contact->email = "test";
        $contact->phone = "test";
        $contact->address = "test";
        $contact->user_id = $user->id;
        $contact->save();
    }
}
