<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GlobalSettingController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => [
                'contact_email' => setting('contact_email', 'info@hpower.ae'),
                'contact_phone' => setting('contact_phone', '+971506164629'),
                'facebook_link' => setting('facebook_link', 'https://www.facebook.com/profile.php?id=100093278287361'),
                'instagram_link' => setting('instagram_link', 'https://www.instagram.com/manpowerforu.ae/'),
                'whatsapp_link' => setting('whatsapp_link', 'https://api.whatsapp.com/send/?phone=971506164629&text&type=phone_number&app_absent=0'),
                'twitter_link' => setting('twitter_link', 'https://twitter.com/Manpower4uUae'),

                'app_title' => setting('app_title'),
                'app_subtitle' => setting('app_subtitle'),
                'meta_description' => setting('meta_description'),
                'meta_keywords' => setting('meta_keywords'),
                'booking_title' => setting('booking_title'),
                'booking_subtitle' => setting('booking_subtitle'),
                'testimonial_title' => setting('testimonial_title'),
                'testimonial_subtitle' => setting('testimonial_subtitle'),
                'service_title' => setting('service_title'),
                'service_subtitle' => setting('service_subtitle'),
                'return_policy_title' => setting('return_policy_title'),
                'return_policy_subtitle' => setting('return_policy_subtitle'),
                'privacy_policy_title' => setting('privacy_policy_title'),
                'privacy_policy_subtitle' => setting('privacy_policy_subtitle'),
                'terms_content' => setting('terms_content'),
            ],
        ], 200);
    }
}
