<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Order Identification
            $table->string('order_id')->unique();

            // Customer Information
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->text('billing_address')->nullable();

            // Order Financials
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            // Payment Information
            $table->string('payment_method')->default('cod');
            $table->string('payment_status')->default('pending');
            $table->string('payment_id')->nullable();
            $table->string('transaction_id')->nullable();

            // Order Status
            $table->enum('order_status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');

            // Notes
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();

            // Discount
            $table->string('discount_code')->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0);

            // Shipping
            $table->string('shipping_tracking')->nullable();

            // Delivery/Cancellation
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();
            $table->softDeletes(); // Add soft delete for order history
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
