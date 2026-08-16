<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    protected $fillable = [
        'title', 'slug', 'banner_image', 'description', 'products', 'status'
    ];

    protected $casts = [
        'products' => 'array',
    ];
}
