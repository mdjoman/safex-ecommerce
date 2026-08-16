<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingPage;
use App\Models\Product;
use Faker\Factory as Faker;

class LandingPageSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $this->command->info('Creating landing pages...');

        $productIds = Product::pluck('id')->toArray();

        for ($i = 1; $i <= 10; $i++) {
            $selectedProducts = $faker->randomElements($productIds, $faker->numberBetween(4, 12));

            LandingPage::updateOrCreate(
                ['slug' => 'landing-' . $i],
                [
                    'title' => $faker->sentence(4),
                    'banner_image' => 'landing/banner-' . $i . '.jpg',
                    'description' => implode(' ', $faker->sentences(20)),
                    'products' => json_encode($selectedProducts),
                    'status' => $faker->randomElement(['active', 'inactive']),
                    'meta_title' => $faker->sentence(5),
                    'meta_description' => $faker->sentence(15),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Landing pages created: ' . LandingPage::count());
    }
}
