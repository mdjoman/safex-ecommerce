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

    /**
     * Generate a unique lead ID
     */
    public static function generateLeadId()
    {
        $lastLead = self::orderBy('id', 'desc')->first();
        $nextId = $lastLead ? $lastLead->id + 1 : 1;
        return 'LEAD-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Generate lead ID and save to model
     */
    public function generateAndSaveLeadId()
    {
        $this->lead_id = self::generateLeadId();
        $this->save();
    }

    /**
     * Boot method to auto-generate lead_id on creation
     */
    protected static function booted()
    {
        static::creating(function ($lead) {
            if (empty($lead->lead_id)) {
                $lead->lead_id = self::generateLeadId();
            }
        });
    }
}
