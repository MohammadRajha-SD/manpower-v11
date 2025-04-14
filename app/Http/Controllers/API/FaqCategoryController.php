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

            $newFaqCategories = $faq_categories->map(function ($fq) {
                return [
                    'id' => $fq->id,
                    'name' => $fq->name,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $newFaqCategories,
                'message' => 'FAQ categories retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
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
                'data' =>  [
                    'id' => $faq_category->id,
                    'name' => $faq_category->name,
                ],
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
