<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmirateController extends Controller
{
    public function index(Request $request){
        try {
            
            $emirates = $request->lang === 'ar' ? config('emirates_ar') : config('emirates');

            return response()->json([
                'status' => 'success',
                'emirates' => $emirates,
                'message' => 'Emirates retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again.',
                'emirates' => [],
            ], 500);
        }
    }
}
