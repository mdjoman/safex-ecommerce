<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\User;
use App\Models\Product;
use Faker\Factory as Faker;

class LeadSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $this->command->info('Creating leads...');

        $leadStatuses = ['new', 'contacted', 'converted', 'lost'];
        $leadSources = ['Product View', 'Add to Cart', 'Checkout Started', 'Order Placed', 'Contact Form', 'WhatsApp'];
        $salesPersonIds = User::where('role', 'sales')->pluck('id')->toArray();
        $productIdsList = Product::pluck('id')->toArray();

        for ($i = 1; $i <= 200; $i++) {
            $leadId = 'LEAD-' . str_pad($i, 6, '0', STR_PAD_LEFT);
            $productId = $faker->randomElement($productIdsList);
            $productName = Product::find($productId)->name ?? 'Unknown Product';

            Lead::updateOrCreate(
                ['lead_id' => $leadId],
                [
                    'customer_name' => $faker->optional(0.8)->name,
                    'phone' => $faker->optional(0.8)->phoneNumber,
                    'email' => $faker->optional(0.8)->email,
                    'interested_product' => $productName,
                    'interested_product_id' => $productId,
                    'source' => $faker->randomElement($leadSources),
                    'status' => $faker->randomElement($leadStatuses),
                    'assigned_sales' => $faker->optional(0.5)->randomElement($salesPersonIds),
                    'notes' => $faker->optional(0.5)->sentence(10),
                    'follow_up_date' => $faker->optional(0.3)->dateTimeBetween('now', '+30 days'),
                    'converted_at' => $faker->optional(0.2)->dateTimeThisYear(),
                    'created_at' => now()->subDays($faker->numberBetween(0, 180)),
                    'updated_at' => now()->subDays($faker->numberBetween(0, 30)),
                ]
            );
        }

        $this->command->info('Leads created: ' . Lead::count());
    }
}
