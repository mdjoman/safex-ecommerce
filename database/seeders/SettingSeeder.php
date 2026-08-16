<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Creating settings...');

        $settings = [
            ['key' => 'site_name', 'value' => 'SafeX Engineering'],
            ['key' => 'site_description', 'value' => 'Leading engineering solutions provider in Bangladesh'],
            ['key' => 'site_logo', 'value' => 'logo.png'],
            ['key' => 'site_favicon', 'value' => 'favicon.ico'],
            ['key' => 'currency', 'value' => 'BDT'],
            ['key' => 'currency_symbol', 'value' => '৳'],
            ['key' => 'tax_rate', 'value' => '15'],
            ['key' => 'shipping_cost', 'value' => '100'],
            ['key' => 'free_shipping_threshold', 'value' => '1000'],
            ['key' => 'order_prefix', 'value' => 'ORD-'],
            ['key' => 'lead_prefix', 'value' => 'LEAD-'],
            ['key' => 'maintenance_mode', 'value' => 'false'],
            ['key' => 'maintenance_message', 'value' => 'We are currently under maintenance. Please check back later.'],
            ['key' => 'email_notifications', 'value' => 'true'],
            ['key' => 'sms_notifications', 'value' => 'false'],
            ['key' => 'whatsapp_number', 'value' => '+8801712345678'],
            ['key' => 'facebook_page', 'value' => 'https://facebook.com/safex'],
            ['key' => 'twitter_handle', 'value' => 'https://twitter.com/safex'],
            ['key' => 'linkedin_page', 'value' => 'https://linkedin.com/company/safex'],
            ['key' => 'youtube_channel', 'value' => 'https://youtube.com/safex'],
            ['key' => 'instagram_page', 'value' => 'https://instagram.com/safex'],
            ['key' => 'address', 'value' => 'House #123, Road #45, Gulshan, Dhaka, Bangladesh'],
            ['key' => 'phone', 'value' => '+880-2-1234567'],
            ['key' => 'email', 'value' => 'info@safex.com'],
            ['key' => 'google_map_url', 'value' => 'https://maps.google.com/safex'],
            ['key' => 'working_hours', 'value' => 'Sunday-Thursday: 9:00 AM - 6:00 PM'],
            ['key' => 'about_us', 'value' => 'SafeX Engineering is a leading engineering solutions provider in Bangladesh...'],
            ['key' => 'privacy_policy', 'value' => 'We are committed to protecting your privacy...'],
            ['key' => 'terms_conditions', 'value' => 'Please read our terms and conditions carefully...'],
            ['key' => 'return_policy', 'value' => 'You can return products within 7 days of delivery...'],
            ['key' => 'shipping_policy', 'value' => 'We deliver nationwide within 3-5 working days...'],
            ['key' => 'meta_title', 'value' => 'SafeX Engineering - Industrial Solutions'],
            ['key' => 'meta_description', 'value' => 'Leading provider of industrial engineering solutions in Bangladesh. Quality products and services.'],
            ['key' => 'meta_keywords', 'value' => 'engineering, industrial, equipment, safety, construction'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }

        $this->command->info('Settings created: ' . Setting::count());
    }
}
