<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ServiceReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ServiceReviewController extends Controller
{
    public function index()
    {
        try {
            $service_reviews = ServiceReview::with('service', 'user')->get();

            return response()->json([
                'status' => 'success',
                'data' => $service_reviews->toArray(),
                'message' => 'Service Reviews retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again.',
                'data' => [],
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review' => 'required|string|max:255',
            'rate' => 'required|integer|between:1,5',
            'service_id' => 'required|exists:services,id',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        try {
            // Create a new ServiceReview
            $review = ServiceReview::create([
                'review' => $request->input('review'),
                'rate' => $request->input('rate'),
                'user_id' => $request->user()->id,
                'service_id' => $request->input('service_id'),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Service review successfully created.',
                'review' => $review,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while saving the review.',
                'x' => $e->getMessage(),
                'review' => [],
            ], 500);
        }
    }
}
