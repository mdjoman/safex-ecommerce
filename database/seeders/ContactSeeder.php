<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Updating contact...');

        Contact::updateOrCreate(
            ['id' => 1],
            [
                'company_name' => 'SafeX Engineering Limited',
                'address' => 'House #123, Road #45, Gulshan-2, Dhaka-1212, Bangladesh',
                'phone' => '+880-2-1234567',
                'phone_alt' => '+880-2-7654321',
                'email' => 'info@safex.com',
                'email_alt' => 'support@safex.com',
                'google_map_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3652.123456!2d90.123456!3d23.123456!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z!5e0!3m2!1sen!2sbd!4v1234567890',
                'latitude' => '23.8103',
                'longitude' => '90.4125',
                'working_hours' => 'Sunday - Thursday: 9:00 AM - 6:00 PM | Friday - Saturday: Closed',
                'facebook_url' => 'https://facebook.com/safexengineering',
                'instagram_url' => 'https://instagram.com/safexengineering',
                'youtube_url' => 'https://youtube.com/safexengineering',
                'linkedin_url' => 'https://linkedin.com/company/safex-engineering',
                'tiktok_url' => 'https://tiktok.com/@safexengineering',
                'twitter_url' => 'https://twitter.com/safexeng',
                'whatsapp_url' => 'https://wa.me/8801712345678',
                'whatsapp_number' => '+8801712345678',
            ]
        );

        $this->command->info('Contact updated successfully!');
    }
}
