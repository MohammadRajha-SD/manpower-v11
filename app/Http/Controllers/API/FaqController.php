<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        try {
            $faqs = Faq::with('category')->get();

            return response()->json([
                'status' => 'success',
                'faqs' => $faqs,
                'message' => 'FAQS retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again.',
                'faqs' => [],
            ], 500);
        }
    }
}
