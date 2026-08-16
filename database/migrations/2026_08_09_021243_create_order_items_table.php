<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->string('sku');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            // Additional fields for better tracking
            $table->string('variant')->nullable();
            $table->json('attributes')->nullable();
            $table->string('status')->default('active');
            $table->decimal('commission', 10, 2)->default(0);

            $table->timestamps();
            $table->softDeletes(); // Optional
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
