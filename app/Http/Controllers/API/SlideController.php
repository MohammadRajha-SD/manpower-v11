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
            $slides = Slide::all();

            return response()->json([
                'success' => true,
                'data' => $slides->toArray(),
                'message' => 'Slides retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
                'data' => [],
            ], 500);
        }
    }
}
