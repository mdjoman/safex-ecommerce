<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name', 'sku', 'category_id', 'sub_category_id', 'brand', 'model',
        'unit', 'selling_price', 'discount_price', 'stock_qty',
        'short_description', 'long_description', 'specification',
        'featured_image', 'gallery', 'slug', 'meta_title',
        'meta_description', 'status'
    ];

    protected $casts = [
        'gallery' => 'array',
        'selling_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function getPriceAttribute()
    {
        return $this->discount_price ?? $this->selling_price;
    }
}
