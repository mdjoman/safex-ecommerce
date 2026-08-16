<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'name', 'subject', 'content', 'recipients', 'schedule_date', 'status'
    ];

    protected $casts = [
        'recipients' => 'array',
        'schedule_date' => 'datetime',
    ];
}
