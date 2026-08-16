<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_name',
        'address',
        'phone',
        'phone_alt',
        'email',
        'email_alt',
        'google_map_embed_url',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'youtube_url',
        'instagram_url',
        'whatsapp_number',
        'working_hours',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'formatted_phone',
        'formatted_whatsapp',
    ];

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute()
    {
        return preg_replace('/[^0-9+]/', '', $this->phone);
    }

    /**
     * Get formatted whatsapp number
     */
    public function getFormattedWhatsappAttribute()
    {
        if ($this->whatsapp_number) {
            return preg_replace('/[^0-9+]/', '', $this->whatsapp_number);
        }
        return $this->formatted_phone;
    }

    /**
     * Get WhatsApp chat URL
     */
    public function getWhatsappUrlAttribute()
    {
        return 'https://wa.me/' . $this->formatted_whatsapp;
    }
}
