<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
     protected $fillable = [
        'id',
        'order_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'billing_address',
        'subtotal',
        'discount',
        'tax',
        'shipping_cost',
        'total',
        'payment_method',
        'payment_status',
        'payment_id',
        'transaction_id',
        'order_status',
        'notes',
        'admin_notes',
        'discount_code',
        'discount_amount',
        'shipping_tracking',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function generateOrderId()
    {
        $this->order_id = 'ORD-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
        $this->save();
    }
}
