<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'email',
        'phone',
        'is_subscribed',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_subscribed' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'status_badge',
        'status_label',
        'formatted_created_at',
    ];

    /**
     * Relationships
     */
    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_subscriber')
                    ->withPivot('status', 'opened_at', 'clicked_at')
                    ->withTimestamps();
    }

    /**
     * Scopes
     */
    public function scopeSubscribed($query)
    {
        return $query->where('is_subscribed', true);
    }

    public function scopeUnsubscribed($query)
    {
        return $query->where('is_subscribed', false);
    }

    public function scopeActive($query)
    {
        return $query->where('is_subscribed', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('email', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%");
        });
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now());
    }

    /**
     * Accessors
     */
    public function getStatusBadgeAttribute()
    {
        if (!$this->is_subscribed) {
            return 'danger';
        }
        return 'success';
    }

    public function getStatusLabelAttribute()
    {
        if (!$this->is_subscribed) {
            return 'Unsubscribed';
        }
        return 'Subscribed';
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('d M Y, h:i A') : null;
    }

    public function getEmailOrPhoneAttribute()
    {
        return $this->email ?? $this->phone ?? 'N/A';
    }

    /**
     * Mutators
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value ? strtolower(trim($value)) : null;
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = $value ? preg_replace('/[^0-9+]/', '', $value) : null;
    }

    /**
     * Methods
     */
    public function isSubscribed()
    {
        return $this->is_subscribed;
    }

    public function subscribe()
    {
        $this->is_subscribed = true;
        $this->save();
        return true;
    }

    public function unsubscribe()
    {
        $this->is_subscribed = false;
        $this->save();
        return true;
    }

    public function toggleSubscription()
    {
        $this->is_subscribed = !$this->is_subscribed;
        $this->save();
        return $this->is_subscribed;
    }

    public function canReceiveEmails()
    {
        return $this->is_subscribed && !empty($this->email);
    }

    public function canReceiveSms()
    {
        return $this->is_subscribed && !empty($this->phone);
    }

    /**
     * Get total active subscribers
     */
    public static function totalActive()
    {
        return static::where('is_subscribed', true)->count();
    }

    /**
     * Get new subscribers this month
     */
    public static function newThisMonth()
    {
        return static::whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year)
                     ->count();
    }

    /**
     * Get unsubscribed count
     */
    public static function totalUnsubscribed()
    {
        return static::where('is_subscribed', false)->count();
    }

    /**
     * Bulk subscribe emails
     */
    public static function bulkSubscribe($emails = [], $phones = [])
    {
        $subscribed = [];
        $existing = [];

        // Subscribe emails
        foreach ($emails as $email) {
            if (empty($email)) continue;

            $subscriber = static::where('email', $email)->first();

            if ($subscriber) {
                if (!$subscriber->is_subscribed) {
                    $subscriber->subscribe();
                    $subscribed[] = $email;
                } else {
                    $existing[] = $email;
                }
            } else {
                static::create([
                    'email' => $email,
                    'is_subscribed' => true,
                ]);
                $subscribed[] = $email;
            }
        }

        // Subscribe phones
        foreach ($phones as $phone) {
            if (empty($phone)) continue;

            $subscriber = static::where('phone', $phone)->first();

            if ($subscriber) {
                if (!$subscriber->is_subscribed) {
                    $subscriber->subscribe();
                    $subscribed[] = $phone;
                } else {
                    $existing[] = $phone;
                }
            } else {
                static::create([
                    'phone' => $phone,
                    'is_subscribed' => true,
                ]);
                $subscribed[] = $phone;
            }
        }

        return [
            'subscribed' => $subscribed,
            'existing' => $existing,
        ];
    }

    /**
     * Bulk unsubscribe
     */
    public static function bulkUnsubscribe($emails = [], $phones = [])
    {
        $unsubscribed = [];

        foreach ($emails as $email) {
            if (empty($email)) continue;

            $subscriber = static::where('email', $email)->first();
            if ($subscriber && $subscriber->is_subscribed) {
                $subscriber->unsubscribe();
                $unsubscribed[] = $email;
            }
        }

        foreach ($phones as $phone) {
            if (empty($phone)) continue;

            $subscriber = static::where('phone', $phone)->first();
            if ($subscriber && $subscriber->is_subscribed) {
                $subscriber->unsubscribe();
                $unsubscribed[] = $phone;
            }
        }

        return $unsubscribed;
    }

    /**
     * Export subscribers to CSV
     */
    public static function exportToCsv()
    {
        $subscribers = static::subscribed()->get();

        $filename = 'subscribers_' . date('Y-m-d') . '.csv';
        $handle = fopen($filename, 'w');

        fputcsv($handle, ['Email', 'Phone', 'Status', 'Subscribed At']);

        foreach ($subscribers as $subscriber) {
            fputcsv($handle, [
                $subscriber->email,
                $subscriber->phone,
                $subscriber->status_label,
                $subscriber->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($handle);

        return $filename;
    }

    /**
     * Get subscriber by email or phone
     */
    public static function findByEmailOrPhone($email = null, $phone = null)
    {
        if ($email) {
            return static::where('email', $email)->first();
        }

        if ($phone) {
            return static::where('phone', $phone)->first();
        }

        return null;
    }

    /**
     * Create or update subscriber
     */
    public static function updateOrCreateSubscriber($email = null, $phone = null, $subscribed = true)
    {
        if (empty($email) && empty($phone)) {
            return null;
        }

        $subscriber = null;

        if ($email) {
            $subscriber = static::where('email', $email)->first();
        }

        if (!$subscriber && $phone) {
            $subscriber = static::where('phone', $phone)->first();
        }

        if ($subscriber) {
            $subscriber->is_subscribed = $subscribed;
            $subscriber->save();
        } else {
            $subscriber = static::create([
                'email' => $email,
                'phone' => $phone,
                'is_subscribed' => $subscribed,
            ]);
        }

        return $subscriber;
    }
}
