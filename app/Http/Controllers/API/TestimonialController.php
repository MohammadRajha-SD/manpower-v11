<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function index(){
        $testimonials = Testimonial::all();

        $img = Setting::where('key', 'banner_img')->first()?->value;

        return response()->json([
            'data' => $testimonials,
            'banner_img' => asset($img) ?? null,
        ]);
    }
}
