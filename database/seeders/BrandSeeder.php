<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Creating brands...');

        $brands = [
            ['name' => 'Siemens', 'slug' => 'siemens', 'description' => 'German engineering excellence', 'display_order' => 1, 'status' => 'active'],
            ['name' => 'ABB', 'slug' => 'abb', 'description' => 'Swiss power and automation', 'display_order' => 2, 'status' => 'active'],
            ['name' => 'Schneider Electric', 'slug' => 'schneider-electric', 'description' => 'French energy management', 'display_order' => 3, 'status' => 'active'],
            ['name' => 'Crompton', 'slug' => 'crompton', 'description' => 'Indian electrical solutions', 'display_order' => 4, 'status' => 'active'],
            ['name' => 'Siemens Energy', 'slug' => 'siemens-energy', 'description' => 'Global energy solutions', 'display_order' => 5, 'status' => 'active'],
            ['name' => 'GE Power', 'slug' => 'ge-power', 'description' => 'American power generation', 'display_order' => 6, 'status' => 'active'],
            ['name' => 'Mitsubishi', 'slug' => 'mitsubishi', 'description' => 'Japanese heavy industry', 'display_order' => 7, 'status' => 'active'],
            ['name' => 'Hitachi', 'slug' => 'hitachi', 'description' => 'Japanese technology leader', 'display_order' => 8, 'status' => 'active'],
            ['name' => 'Toshiba', 'slug' => 'toshiba', 'description' => 'Japanese electrical solutions', 'display_order' => 9, 'status' => 'active'],
            ['name' => 'Panasonic', 'slug' => 'panasonic', 'description' => 'Japanese electronics', 'display_order' => 10, 'status' => 'active'],
            ['name' => 'LG', 'slug' => 'lg', 'description' => 'South Korean electronics', 'display_order' => 11, 'status' => 'active'],
            ['name' => 'Samsung', 'slug' => 'samsung', 'description' => 'South Korean technology', 'display_order' => 12, 'status' => 'active'],
            ['name' => 'Nokia', 'slug' => 'nokia', 'description' => 'Finnish technology', 'display_order' => 13, 'status' => 'active'],
            ['name' => 'Honeywell', 'slug' => 'honeywell', 'description' => 'American technology', 'display_order' => 14, 'status' => 'active'],
            ['name' => 'Bosch', 'slug' => 'bosch', 'description' => 'German engineering', 'display_order' => 15, 'status' => 'active'],
            ['name' => 'Delta', 'slug' => 'delta', 'description' => 'Taiwanese electronics', 'display_order' => 16, 'status' => 'active'],
            ['name' => 'Eaton', 'slug' => 'eaton', 'description' => 'American power management', 'display_order' => 17, 'status' => 'active'],
            ['name' => 'Emerson', 'slug' => 'emerson', 'description' => 'American industrial technology', 'display_order' => 18, 'status' => 'active'],
            ['name' => 'Rockwell', 'slug' => 'rockwell', 'description' => 'American automation', 'display_order' => 19, 'status' => 'active'],
            ['name' => 'Siemens Industry', 'slug' => 'siemens-industry', 'description' => 'Industrial automation', 'display_order' => 20, 'status' => 'active'],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => $brand['slug']],
                $brand
            );
        }

        $this->command->info('Brands created: ' . Brand::count());
    }
}
