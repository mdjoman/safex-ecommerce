<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $this->command->info('Creating 1000+ products with CDN images...');

        $categoryIds = Category::pluck('id')->toArray();
        $subCategoryIds = SubCategory::pluck('id')->toArray();
        $brandIds = Brand::pluck('id')->toArray();

        // Free CDN Image URLs for different product categories
        $imageUrls = [
            // Engineering & Industrial
            'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=600&h=600&fit=crop&crop=center&auto=format',
            'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=600&h=600&fit=crop&crop=center&auto=format',
            'https://images.unsplash.com/photo-1558002038-1055907df827?w=600&h=600&fit=crop&crop=center&auto=format',

            // Security & Safety
            'https://images.unsplash.com/photo-1557324232-b8917d3c3dcb?w=600&h=600&fit=crop&crop=center&auto=format',
            'https://images.unsplash.com/photo-1619160151837-08b793c7b89b?w=600&h=600&fit=crop&crop=center&auto=format',
            'https://images.unsplash.com/photo-1558002038-1055907df827?w=600&h=600&fit=crop&crop=center&auto=format',

            // Technology & Gadgets
            'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=600&h=600&fit=crop&crop=center&auto=format',
            'https://images.unsplash.com/photo-1555000006-2cf8f08aa3a0?w=600&h=600&fit=crop&crop=center&auto=format',
            'https://images.unsplash.com/photo-1558981285-6f0c94958bb6?w=600&h=600&fit=crop&crop=center&auto=format',

            // Agriculture & Machinery
            'https://images.unsplash.com/photo-1530268729831-4b0b9e170218?w=600&h=600&fit=crop&crop=center&auto=format',
            'https://images.unsplash.com/photo-1581579438747-1dc8d17bbce4?w=600&h=600&fit=crop&crop=center&auto=format',

            // Electrical & Electronics
            'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?w=600&h=600&fit=crop&crop=center&auto=format',
            'https://images.unsplash.com/photo-1581092335874-0b7e5a2d20d8?w=600&h=600&fit=crop&crop=center&auto=format',
        ];

        $productNames = [
            'Industrial Cable', 'Transformer', 'Circuit Breaker', 'Motor', 'Generator',
            'Switchgear', 'Control Panel', 'Solar Panel', 'Inverter', 'Battery',
            'PLC System', 'SCADA System', 'HMI Panel', 'Sensor', 'Actuator',
            'Pressure Gauge', 'Temperature Sensor', 'Flow Meter', 'Level Sensor',
            'Control Valve', 'Pump', 'Compressor', 'Conveyor', 'Gear System',
            'Bearing Set', 'Hydraulic System', 'Steel Bar', 'Cement Bag', 'Brick',
            'Timber Sheet', 'Glass Panel', 'Industrial Paint', 'Safety Helmet',
            'Protective Gloves', 'Safety Goggles', 'Safety Harness', 'Fire Extinguisher',
            'First Aid Kit', 'Power Drill', 'Hand Tool Set', 'Measuring Instrument',
            'Cutting Tool', 'Welding Machine', 'Lifting Equipment', 'UPS System',
            'Power Generator', 'Distribution Panel', 'Cable Tray', 'Conduit Pipe',
            'Electrical Fitting', 'Lighting System', 'Fan System', 'Ventilation System',
            'Heating System', 'Cooling System', 'Compressor Parts', 'Pump Parts',
            'Motor Parts', 'Gear Parts', 'Bearing Parts', 'Hydraulic Parts',
            'Pneumatic System', 'Vacuum System', 'Control System', 'Measurement System',
            'Testing Equipment', 'Calibration Equipment', 'Maintenance Tools',
            'Protective Clothing', 'Safety Signage', 'Emergency Equipment'
        ];

        $descriptions = [
            'High quality industrial product for professional use.',
            'Durable and reliable solution for industrial applications.',
            'Premium quality product with excellent performance.',
            'Advanced technology product for modern industry.',
            'Heavy duty product designed for demanding environments.',
            'Precision engineered product for accurate results.',
            'Cost-effective solution without compromising quality.',
            'Energy efficient product for sustainable operations.',
            'Easy to install and maintain product.',
            'Certified product meeting international standards.',
        ];

        $units = ['piece', 'set', 'box', 'kg', 'meter', 'liter'];

        for ($i = 1; $i <= 1000; $i++) {
            $name = $faker->randomElement($productNames) . ' ' . $faker->randomNumber(3);
            $sku = 'SKU-' . strtoupper($faker->bothify('???')) . '-' . str_pad($i, 6, '0', STR_PAD_LEFT);
            $slug = Str::slug($name . '-' . $i);

            $sellingPrice = $faker->randomFloat(2, 100, 10000);
            $hasDiscount = $faker->boolean(30);
            $discountPrice = $hasDiscount ? round($sellingPrice * $faker->randomFloat(2, 0.1, 0.4), 2) : null;

            // Random gallery images (3-5 images)
            $galleryCount = $faker->numberBetween(3, 5);
            $gallery = [];
            for ($j = 0; $j < $galleryCount; $j++) {
                $gallery[] = $faker->randomElement($imageUrls);
            }

            Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'name' => $name,
                    'category_id' => $faker->randomElement($categoryIds),
                    'sub_category_id' => $faker->randomElement($subCategoryIds),
                    'brand_id' => $faker->randomElement($brandIds),
                    'model' => 'MOD-' . strtoupper($faker->bothify('???')) . '-' . $faker->randomNumber(4),
                    'unit' => $faker->randomElement($units),
                    'selling_price' => $sellingPrice,
                    'discount_price' => $discountPrice,
                    'stock_qty' => $faker->numberBetween(0, 500),
                    'short_description' => $faker->randomElement($descriptions),
                    'long_description' => implode(' ', $faker->sentences(5)),
                    'specification' => '<ul><li>' . implode('</li><li>', $faker->words(10)) . '</li></ul>',
                    'featured_image' => $faker->randomElement($imageUrls),
                    'gallery' => json_encode($gallery),
                    'slug' => $slug,
                    'meta_title' => $name,
                    'meta_description' => $faker->sentence(15),
                    'status' => $faker->randomElement(['active', 'inactive']),
                    'created_at' => now()->subDays($faker->numberBetween(0, 365)),
                    'updated_at' => now()->subDays($faker->numberBetween(0, 30)),
                ]
            );
        }

        $this->command->info('Products created: ' . Product::count());
    }
}
