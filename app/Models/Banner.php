<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'badge',
        'description',
        'image',
        'button_text',
        'button_url',
        'order',
        'status',
        'start_date',
        'end_date',
        'stat1_label',
        'stat1_value',
        'stat2_label',
        'stat2_value',
        'stat3_label',
        'stat3_value',
        'text_color',
        'bg_color',
        'button_color',
        'button_hover_color',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'order' => 'integer',
        'status' => 'string',
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
        'stats',
        'is_active',
        'date_range',
        'button_style',
        'background_style',
        'text_color_style',
    ];

    /**
     * Default values for color fields
     */
    protected $attributes = [
        'text_color' => '#FFFFFF',
        'button_color' => '#0637A1',
        'button_hover_color' => '#03246E',
    ];

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where(function($q) {
                         $q->whereNull('start_date')
                           ->orWhere('start_date', '<=', now());
                     })
                     ->where(function($q) {
                         $q->whereNull('end_date')
                           ->orWhere('end_date', '>=', now());
                     });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Get stats as array
     */
    public function getStatsAttribute()
    {
        return [
            [
                'label' => $this->stat1_label ?? 'Year Warranty',
                'value' => $this->stat1_value ?? '2',
            ],
            [
                'label' => $this->stat2_label ?? 'Year Free Service',
                'value' => $this->stat2_value ?? '4',
            ],
            [
                'label' => $this->stat3_label ?? 'Support',
                'value' => $this->stat3_value ?? '24/7',
            ],
        ];
    }

    /**
     * Check if banner is currently active
     */
    public function getIsActiveAttribute()
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->start_date && $this->start_date > now()) {
            return false;
        }

        if ($this->end_date && $this->end_date < now()) {
            return false;
        }

        return true;
    }

    /**
     * Get date range
     */
    public function getDateRangeAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->format('d M Y') . ' - ' . $this->end_date->format('d M Y');
        }
        return null;
    }

    /**
     * Get button style
     */
    public function getButtonStyleAttribute()
    {
        return [
            'background' => $this->button_color ?? '#0637A1',
            'hover_background' => $this->button_hover_color ?? '#03246E',
        ];
    }

    /**
     * Get background style
     */
    public function getBackgroundStyleAttribute()
    {
        if ($this->bg_color) {
            return 'background: ' . $this->bg_color . ';';
        }
        return 'background: linear-gradient(135deg, #021447 0%, #03246E 100%);';
    }

    /**
     * Get text color style
     */
    public function getTextColorStyleAttribute()
    {
        return 'color: ' . ($this->text_color ?? '#FFFFFF') . ';';
    }

    /**
     * Get button HTML style
     */
    public function getButtonHtmlStyleAttribute()
    {
        $bg = $this->button_color ?? '#0637A1';
        $hover = $this->button_hover_color ?? '#03246E';
        return "background: {$bg}; hover:background: {$hover};";
    }

    /**
     * Get text color for use in blade
     */
    public function getTextColorAttribute($value)
    {
        return $value ?? '#FFFFFF';
    }

    /**
     * Get button color for use in blade
     */
    public function getButtonColorAttribute($value)
    {
        return $value ?? '#0637A1';
    }

    /**
     * Get button hover color for use in blade
     */
    public function getButtonHoverColorAttribute($value)
    {
        return $value ?? '#03246E';
    }
}
