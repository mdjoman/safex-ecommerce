<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    protected $fillable = [
        'lead_id', 'customer_name', 'phone', 'email', 'interested_product',
        'source', 'status', 'assigned_sales', 'notes'
    ];

    public function salesPerson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_sales');
    }

    public function generateLeadId()
    {
        $this->lead_id = 'LEAD-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
        $this->save();
    }
}
