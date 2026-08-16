<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscriber;
use Faker\Factory as Faker;

class SubscriberSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $this->command->info('Creating subscribers...');

        for ($i = 1; $i <= 50; $i++) {
            Subscriber::updateOrCreate(
                ['email' => 'subscriber' . $i . '@example.com'],
                [
                    'is_subscribed' => $faker->boolean(80),
                    'created_at' => now()->subDays($faker->numberBetween(0, 365)),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Subscribers created: ' . Subscriber::count());
    }
}
