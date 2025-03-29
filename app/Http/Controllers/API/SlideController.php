<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function index()
    {
        try {
            $slides = Slide::where('status', 1)->get();

            return response()->json([
                'status' => 'success',
                'slides' => $slides,
                'message' => 'Slides retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again.',
                'slides' => [],
            ], 500);
        }
    }
}
