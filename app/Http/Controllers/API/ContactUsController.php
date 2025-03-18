<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ServiceReview;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function index()
    {
        try {
            $service_reviews = ServiceReview::with('service')->get();

            return response()->json([
                'success' => true,
                'data' => $service_reviews->toArray(),
                'message' => 'Service Reviews retrieved successfully',
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
