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

            $newFaqs = $faqs->map(function ($fq) {
                return [
                    'id' => $fq->id,
                    'question' => $fq->question,
                    'answer' => $fq->answer,
                    'faq_category_id' => $fq->faq_category_id,
                    'faq_category_name' => $fq->category->name,
                ];
            });
            
            return response()->json([
                'status' => 'success',
                'data' => $newFaqs,
                'message' => 'FAQS retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again.',
                'data' => [],
            ], 500);
        }
    }
}
