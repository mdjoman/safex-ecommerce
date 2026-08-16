<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

if (!function_exists('setting')) {
    /**
     * Get a setting value from database
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        try {
            // Check if table exists
            if (app()->runningInConsole()) {
                return $default;
            }

            if (!Schema::hasTable('settings')) {
                return $default;
            }

            // Try to get from cache first
            $settings = Cache::remember('site_settings', 3600, function () {
                return Setting::pluck('value', 'key')->toArray();
            });

            return $settings[$key] ?? $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('getSetting')) {
    /**
     * Alias for setting function
     */
    function getSetting($key, $default = null)
    {
        return setting($key, $default);
    }
}

if (!function_exists('getAllSettings')) {
    /**
     * Get all settings as array
     *
     * @return array
     */
    function getAllSettings()
    {
        try {
            if (!Schema::hasTable('settings')) {
                return [];
            }

            return Cache::remember('all_site_settings', 3600, function () {
                return Setting::pluck('value', 'key')->toArray();
            });
        } catch (\Exception $e) {
            return [];
        }
    }
}

if (!function_exists('updateSetting')) {
    /**
     * Update or create a setting
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    function updateSetting($key, $value)
    {
        try {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
            Cache::forget('site_settings');
            Cache::forget('all_site_settings');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
