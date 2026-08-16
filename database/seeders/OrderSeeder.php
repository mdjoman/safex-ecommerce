<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $this->command->info('Creating orders...');

        $orderStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentMethods = ['cod', 'bkash', 'nagad', 'bank_transfer'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];
        $userIdsList = User::where('role', 'user')->pluck('id')->toArray();
        $productIdsForOrder = Product::pluck('id')->toArray();

        for ($i = 1; $i <= 300; $i++) {
            $userId = $faker->optional(0.7)->randomElement($userIdsList);
            $customerName = $userId ? User::find($userId)->name : $faker->name;
            $customerEmail = $userId ? User::find($userId)->email : $faker->email;
            $customerPhone = $faker->phoneNumber;

            $subtotal = $faker->randomFloat(2, 100, 10000);
            $discount = $faker->optional(0.3)->randomFloat(2, 0, $subtotal * 0.2);
            $tax = round($subtotal * 0.15, 2);
            $shippingCost = $faker->optional(0.3)->randomFloat(2, 50, 300);
            $total = round($subtotal + $tax + ($shippingCost ?? 0) - ($discount ?? 0), 2);

            $orderId = 'ORD-' . str_pad($i, 6, '0', STR_PAD_LEFT);

            $deliveredAt = null;
            $cancelledAt = null;
            $cancellationReason = null;
            $orderStatus = $faker->randomElement($orderStatuses);

            if ($orderStatus === 'delivered') {
                $deliveredAt = $faker->dateTimeThisYear();
            } elseif ($orderStatus === 'cancelled') {
                $cancelledAt = $faker->dateTimeThisYear();
                $cancellationReason = $faker->sentence(5);
            }

            $order = Order::updateOrCreate(
                ['order_id' => $orderId],
                [
                    'user_id' => $userId,
                    'customer_name' => $customerName,
                    'customer_email' => $customerEmail,
                    'customer_phone' => $customerPhone,
                    'shipping_address' => $faker->address,
                    'billing_address' => $faker->optional(0.5)->address,
                    'subtotal' => $subtotal,
                    'discount' => $discount ?? 0,
                    'tax' => $tax,
                    'shipping_cost' => $shippingCost ?? 0,
                    'total' => $total,
                    'payment_method' => $faker->randomElement($paymentMethods),
                    'payment_status' => $faker->randomElement($paymentStatuses),
                    'order_status' => $orderStatus,
                    'notes' => $faker->optional(0.3)->sentence(8),
                    'admin_notes' => $faker->optional(0.2)->sentence(6),
                    'shipping_tracking' => $faker->optional(0.3)->bothify('TRK-##########'),
                    'discount_code' => $faker->optional(0.2)->bothify('DISC-####'),
                    'discount_amount' => $discount ?? 0,
                    'payment_id' => $faker->optional(0.3)->bothify('PAY-####-####'),
                    'transaction_id' => $faker->optional(0.3)->bothify('TXN-####-####'),
                    'delivered_at' => $deliveredAt,
                    'cancelled_at' => $cancelledAt,
                    'cancellation_reason' => $cancellationReason,
                    'created_at' => now()->subDays($faker->numberBetween(0, 180)),
                    'updated_at' => now()->subDays($faker->numberBetween(0, 30)),
                ]
            );

            // Create order items
            $numItems = $faker->numberBetween(1, 5);
            $selectedProducts = $faker->randomElements($productIdsForOrder, $numItems);

            foreach ($selectedProducts as $productId) {
                $product = Product::find($productId);
                if ($product) {
                    $quantity = $faker->numberBetween(1, 10);
                    $price = $product->selling_price;

                    $itemSubtotal = round($price * $quantity, 2);
                    $itemDiscount = $faker->optional(0.2)->randomFloat(2, 0, $itemSubtotal * 0.1);
                    $itemTax = round($itemSubtotal * 0.15, 2);
                    $itemTotal = round($itemSubtotal - ($itemDiscount ?? 0) + $itemTax, 2);

                    $variants = ['Standard', 'Deluxe', 'Premium', 'Economy', 'Pro', 'Basic', 'Advanced'];
                    $colors = ['Red', 'Blue', 'Green', 'Black', 'White', 'Silver', 'Gold', 'Bronze'];
                    $sizes = ['Small', 'Medium', 'Large', 'XL', 'XXL', 'XXXL'];
                    $materials = ['Steel', 'Aluminum', 'Plastic', 'Wood', 'Composite', 'Carbon Fiber', 'Titanium'];

                    OrderItem::updateOrCreate(
                        [
                            'order_id' => $order->id,
                            'product_id' => $productId,
                        ],
                        [
                            'product_name' => $product->name,
                            'sku' => $product->sku,
                            'quantity' => $quantity,
                            'price' => $price,
                            'subtotal' => $itemSubtotal,
                            'discount' => $itemDiscount ?? 0,
                            'tax' => $itemTax,
                            'total' => $itemTotal,
                            'variant' => $faker->randomElement($variants),
                            'attributes' => json_encode([
                                'color' => $faker->randomElement($colors),
                                'size' => $faker->randomElement($sizes),
                                'material' => $faker->randomElement($materials),
                            ]),
                            'status' => $faker->randomElement(['active', 'pending', 'cancelled']),
                            'commission' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }

        $this->command->info('Orders created: ' . Order::count());
        $this->command->info('Order Items created: ' . OrderItem::count());
    }
}
