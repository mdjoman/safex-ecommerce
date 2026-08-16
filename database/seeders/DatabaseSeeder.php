<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            ProductSeeder::class,
            BannerSeeder::class,
            LandingPageSeeder::class,
            LeadSeeder::class,
            OrderSeeder::class,
            SubscriberSeeder::class,
            SettingSeeder::class,
            ContactSeeder::class,
        ]);
    }
}
