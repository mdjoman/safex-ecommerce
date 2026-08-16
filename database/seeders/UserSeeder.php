<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $this->command->info('Creating users...');

        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@safex.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Sales Users (5)
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => 'sales' . $i . '@safex.com'],
                [
                    'name' => $faker->name,
                    'password' => Hash::make('password'),
                    'role' => 'sales',
                    'email_verified_at' => now(),
                ]
            );
        }

        // Regular Users (50)
        for ($i = 1; $i <= 50; $i++) {
            User::updateOrCreate(
                ['email' => 'user' . $i . '@example.com'],
                [
                    'name' => $faker->name,
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'email_verified_at' => $faker->optional(0.8)->dateTimeThisYear(),
                ]
            );
        }

        $this->command->info('Users created: ' . User::count());
    }
}
