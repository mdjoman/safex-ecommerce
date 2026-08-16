<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubCategory;

class SubCategorySeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Creating sub-categories...');

        $subCategories = [
            // Electrical Equipment
            ['category_id' => 1, 'name' => 'Cables', 'slug' => 'cables', 'description' => 'Industrial cables and wires', 'display_order' => 1, 'status' => 'active'],
            ['category_id' => 1, 'name' => 'Switchgear', 'slug' => 'switchgear', 'description' => 'Electrical switchgear', 'display_order' => 2, 'status' => 'active'],
            ['category_id' => 1, 'name' => 'Transformers', 'slug' => 'transformers', 'description' => 'Power transformers', 'display_order' => 3, 'status' => 'active'],
            ['category_id' => 1, 'name' => 'Circuit Breakers', 'slug' => 'circuit-breakers', 'description' => 'Safety devices', 'display_order' => 4, 'status' => 'active'],
            ['category_id' => 1, 'name' => 'Motors', 'slug' => 'motors', 'description' => 'Electric motors', 'display_order' => 5, 'status' => 'active'],
            ['category_id' => 1, 'name' => 'Generators', 'slug' => 'generators', 'description' => 'Power generators', 'display_order' => 6, 'status' => 'active'],

            // Mechanical Equipment
            ['category_id' => 2, 'name' => 'Pumps', 'slug' => 'pumps', 'description' => 'Industrial pumps', 'display_order' => 1, 'status' => 'active'],
            ['category_id' => 2, 'name' => 'Compressors', 'slug' => 'compressors', 'description' => 'Air compressors', 'display_order' => 2, 'status' => 'active'],
            ['category_id' => 2, 'name' => 'Conveyors', 'slug' => 'conveyors', 'description' => 'Material handling', 'display_order' => 3, 'status' => 'active'],
            ['category_id' => 2, 'name' => 'Gears', 'slug' => 'gears', 'description' => 'Mechanical gears', 'display_order' => 4, 'status' => 'active'],
            ['category_id' => 2, 'name' => 'Bearings', 'slug' => 'bearings', 'description' => 'Industrial bearings', 'display_order' => 5, 'status' => 'active'],
            ['category_id' => 2, 'name' => 'Hydraulics', 'slug' => 'hydraulics', 'description' => 'Hydraulic systems', 'display_order' => 6, 'status' => 'active'],

            // Construction Materials
            ['category_id' => 3, 'name' => 'Steel', 'slug' => 'steel', 'description' => 'Construction steel', 'display_order' => 1, 'status' => 'active'],
            ['category_id' => 3, 'name' => 'Cement', 'slug' => 'cement', 'description' => 'Construction cement', 'display_order' => 2, 'status' => 'active'],
            ['category_id' => 3, 'name' => 'Bricks', 'slug' => 'bricks', 'description' => 'Building bricks', 'display_order' => 3, 'status' => 'active'],
            ['category_id' => 3, 'name' => 'Timber', 'slug' => 'timber', 'description' => 'Wood products', 'display_order' => 4, 'status' => 'active'],
            ['category_id' => 3, 'name' => 'Glass', 'slug' => 'glass', 'description' => 'Building glass', 'display_order' => 5, 'status' => 'active'],
            ['category_id' => 3, 'name' => 'Paint', 'slug' => 'paint', 'description' => 'Industrial paint', 'display_order' => 6, 'status' => 'active'],

            // Safety Equipment
            ['category_id' => 4, 'name' => 'Helmets', 'slug' => 'helmets', 'description' => 'Safety helmets', 'display_order' => 1, 'status' => 'active'],
            ['category_id' => 4, 'name' => 'Gloves', 'slug' => 'gloves', 'description' => 'Protective gloves', 'display_order' => 2, 'status' => 'active'],
            ['category_id' => 4, 'name' => 'Goggles', 'slug' => 'goggles', 'description' => 'Eye protection', 'display_order' => 3, 'status' => 'active'],
            ['category_id' => 4, 'name' => 'Harnesses', 'slug' => 'harnesses', 'description' => 'Safety harnesses', 'display_order' => 4, 'status' => 'active'],
            ['category_id' => 4, 'name' => 'Fire Extinguishers', 'slug' => 'fire-extinguishers', 'description' => 'Fire safety', 'display_order' => 5, 'status' => 'active'],
            ['category_id' => 4, 'name' => 'First Aid Kits', 'slug' => 'first-aid-kits', 'description' => 'Medical supplies', 'display_order' => 6, 'status' => 'active'],

            // Industrial Tools
            ['category_id' => 5, 'name' => 'Power Tools', 'slug' => 'power-tools', 'description' => 'Electric tools', 'display_order' => 1, 'status' => 'active'],
            ['category_id' => 5, 'name' => 'Hand Tools', 'slug' => 'hand-tools', 'description' => 'Manual tools', 'display_order' => 2, 'status' => 'active'],
            ['category_id' => 5, 'name' => 'Measuring Tools', 'slug' => 'measuring-tools', 'description' => 'Precision tools', 'display_order' => 3, 'status' => 'active'],
            ['category_id' => 5, 'name' => 'Cutting Tools', 'slug' => 'cutting-tools', 'description' => 'Cutting equipment', 'display_order' => 4, 'status' => 'active'],
            ['category_id' => 5, 'name' => 'Welding Tools', 'slug' => 'welding-tools', 'description' => 'Welding equipment', 'display_order' => 5, 'status' => 'active'],
            ['category_id' => 5, 'name' => 'Lifting Tools', 'slug' => 'lifting-tools', 'description' => 'Material handling', 'display_order' => 6, 'status' => 'active'],

            // Power Systems
            ['category_id' => 6, 'name' => 'Solar Panels', 'slug' => 'solar-panels', 'description' => 'Solar energy', 'display_order' => 1, 'status' => 'active'],
            ['category_id' => 6, 'name' => 'Inverters', 'slug' => 'inverters', 'description' => 'Power conversion', 'display_order' => 2, 'status' => 'active'],
            ['category_id' => 6, 'name' => 'Batteries', 'slug' => 'batteries', 'description' => 'Energy storage', 'display_order' => 3, 'status' => 'active'],
            ['category_id' => 6, 'name' => 'UPS', 'slug' => 'ups', 'description' => 'Uninterruptible power', 'display_order' => 4, 'status' => 'active'],
            ['category_id' => 6, 'name' => 'Generators', 'slug' => 'generators', 'description' => 'Power generation', 'display_order' => 5, 'status' => 'active'],
            ['category_id' => 6, 'name' => 'Transformers', 'slug' => 'power-transformers', 'description' => 'Power transmission', 'display_order' => 6, 'status' => 'active'],

            // Automation Systems
            ['category_id' => 7, 'name' => 'PLC Systems', 'slug' => 'plc-systems', 'description' => 'Programmable controllers', 'display_order' => 1, 'status' => 'active'],
            ['category_id' => 7, 'name' => 'SCADA', 'slug' => 'scada', 'description' => 'Supervisory control', 'display_order' => 2, 'status' => 'active'],
            ['category_id' => 7, 'name' => 'HMI', 'slug' => 'hmi', 'description' => 'Human-machine interface', 'display_order' => 3, 'status' => 'active'],
            ['category_id' => 7, 'name' => 'Sensors', 'slug' => 'sensors', 'description' => 'Industrial sensors', 'display_order' => 4, 'status' => 'active'],
            ['category_id' => 7, 'name' => 'Actuators', 'slug' => 'actuators', 'description' => 'Control actuators', 'display_order' => 5, 'status' => 'active'],
            ['category_id' => 7, 'name' => 'Robotics', 'slug' => 'robotics', 'description' => 'Industrial robots', 'display_order' => 6, 'status' => 'active'],

            // Instrumentation
            ['category_id' => 8, 'name' => 'Pressure Gauges', 'slug' => 'pressure-gauges', 'description' => 'Pressure measurement', 'display_order' => 1, 'status' => 'active'],
            ['category_id' => 8, 'name' => 'Temperature Sensors', 'slug' => 'temperature-sensors', 'description' => 'Temperature measurement', 'display_order' => 2, 'status' => 'active'],
            ['category_id' => 8, 'name' => 'Flow Meters', 'slug' => 'flow-meters', 'description' => 'Flow measurement', 'display_order' => 3, 'status' => 'active'],
            ['category_id' => 8, 'name' => 'Level Sensors', 'slug' => 'level-sensors', 'description' => 'Level measurement', 'display_order' => 4, 'status' => 'active'],
            ['category_id' => 8, 'name' => 'Analyzers', 'slug' => 'analyzers', 'description' => 'Process analysis', 'display_order' => 5, 'status' => 'active'],
            ['category_id' => 8, 'name' => 'Control Valves', 'slug' => 'control-valves', 'description' => 'Flow control', 'display_order' => 6, 'status' => 'active'],
        ];

        foreach ($subCategories as $subCategory) {
            SubCategory::updateOrCreate(
                ['slug' => $subCategory['slug']],
                $subCategory
            );
        }

        $this->command->info('Sub-categories created: ' . SubCategory::count());
    }
}
