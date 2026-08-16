<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share settings with all views
        View::composer('*', function ($view) {
            $view->with('settings', $this->getSettings());
        });
    }

    /**
     * Get all settings with caching
     */
    private function getSettings()
    {
        return Cache::remember('site_settings', 3600, function () {
            try {
                if (!Schema::hasTable('settings')) {
                    return $this->getDefaultSettings();
                }

                $settings = Setting::pluck('value', 'key')->toArray();
                return !empty($settings) ? $settings : $this->getDefaultSettings();
            } catch (\Exception $e) {
                return $this->getDefaultSettings();
            }
        });
    }

    /**
     * Default settings fallback
     */
    private function getDefaultSettings()
    {
        return [
            'site_name' => 'SafeX Engineering',
            'site_description' => 'Leading engineering solutions provider in Bangladesh',
            'site_logo' => 'logo.png',
            'site_favicon' => 'favicon.ico',
            'currency' => 'BDT',
            'currency_symbol' => '৳',
            'tax_rate' => '15',
            'shipping_cost' => '100',
            'free_shipping_threshold' => '1000',
            'order_prefix' => 'ORD-',
            'lead_prefix' => 'LEAD-',
            'maintenance_mode' => 'false',
            'maintenance_message' => 'We are currently under maintenance. Please check back later.',
            'email_notifications' => 'true',
            'sms_notifications' => 'false',
            'whatsapp_number' => '+8801712345678',
            'facebook_page' => 'https://facebook.com/safex',
            'twitter_handle' => 'https://twitter.com/safex',
            'linkedin_page' => 'https://linkedin.com/company/safex',
            'youtube_channel' => 'https://youtube.com/safex',
            'instagram_page' => 'https://instagram.com/safex',
            'address' => 'House #123, Road #45, Gulshan, Dhaka, Bangladesh',
            'phone' => '+880-2-1234567',
            'email' => 'info@safex.com',
            'google_map_url' => 'https://maps.google.com/safex',
            'working_hours' => 'Sunday-Thursday: 9:00 AM - 6:00 PM',
            'about_us' => 'SafeX Engineering is a leading engineering solutions provider in Bangladesh...',
            'privacy_policy' => 'We are committed to protecting your privacy...',
            'terms_conditions' => 'Please read our terms and conditions carefully...',
            'return_policy' => 'You can return products within 7 days of delivery...',
            'shipping_policy' => 'We deliver nationwide within 3-5 working days...',
            'meta_title' => 'SafeX Engineering - Industrial Solutions',
            'meta_description' => 'Leading provider of industrial engineering solutions in Bangladesh. Quality products and services.',
            'meta_keywords' => 'engineering, industrial, equipment, safety, construction',
        ];
    }
}
