<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Creating categories...');

        $categories = [
            ['name' => 'Electrical Equipment', 'slug' => 'electrical-equipment', 'description' => 'Complete electrical solutions', 'display_order' => 1, 'status' => 'active'],
            ['name' => 'Mechanical Equipment', 'slug' => 'mechanical-equipment', 'description' => 'Mechanical systems and parts', 'display_order' => 2, 'status' => 'active'],
            ['name' => 'Construction Materials', 'slug' => 'construction-materials', 'description' => 'Building and construction supplies', 'display_order' => 3, 'status' => 'active'],
            ['name' => 'Safety Equipment', 'slug' => 'safety-equipment', 'description' => 'Industrial safety and protection', 'display_order' => 4, 'status' => 'active'],
            ['name' => 'Industrial Tools', 'slug' => 'industrial-tools', 'description' => 'Professional tools and equipment', 'display_order' => 5, 'status' => 'active'],
            ['name' => 'Power Systems', 'slug' => 'power-systems', 'description' => 'Power generation and distribution', 'display_order' => 6, 'status' => 'active'],
            ['name' => 'Automation Systems', 'slug' => 'automation-systems', 'description' => 'Industrial automation solutions', 'display_order' => 7, 'status' => 'active'],
            ['name' => 'Instrumentation', 'slug' => 'instrumentation', 'description' => 'Measurement and control', 'display_order' => 8, 'status' => 'active'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('Categories created: ' . Category::count());
    }
}
