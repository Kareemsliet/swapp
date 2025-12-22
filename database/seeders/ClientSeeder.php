<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::create([
            "name" => "eslam Elghonemy",
            "email" => "eslam@example.com",
            "phone" => "1234567890",
            "birthdate" => "1990-01-01",
            "email_verified_at" => now(),
            "summary" => "This is a sample client.",
            "password" => "password123",
        ]);
    }
}
