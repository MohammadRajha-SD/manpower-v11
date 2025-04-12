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
            ]
        ], 200);
    }
}
