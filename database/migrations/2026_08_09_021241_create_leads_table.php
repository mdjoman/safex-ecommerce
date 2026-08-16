<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_id')->unique();
            $table->string('customer_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('interested_product')->nullable();
            $table->foreignId('interested_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('source')->nullable();
            $table->enum('status', ['new', 'contacted', 'converted', 'lost'])->default('new');
            $table->foreignId('assigned_sales')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->dateTime('follow_up_date')->nullable();
            $table->dateTime('converted_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->integer('rating')->nullable();
            $table->string('budget_range')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Optional: for soft delete
        });
    
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
