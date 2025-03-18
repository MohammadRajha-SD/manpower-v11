<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;

class FaqCategoryController extends Controller
{
    public function index()
    {
        try {
            $faq_categories = FaqCategory::all();

            return response()->json([
                'success' => true,
                'data' => $faq_categories->toArray(),
                'message' => 'FAQ categories retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

    public function show($id)
    {
        $faq_category = FaqCategory::find($id);

        if ($faq_category) {
            return response()->json([
                'success' => true,
                'data' => $faq_category->toArray(),
                'message' => 'Faq Category retrieved successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'FAQ category not found.'
            ], 404);
        }
    }
}
