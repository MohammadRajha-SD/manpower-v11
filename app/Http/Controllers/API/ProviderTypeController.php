<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProviderType;
use Illuminate\Http\Request;

class ProviderTypeController extends Controller
{
    
    public function index(Request $request)
    {
        try {
            $lang = $request->lang ?? 'en';

            $types = ProviderType::where('disabled', 0)->get();

            return response()->json([
                'status' => 'success',
                'data' => $types->map(function($type)use ($lang) {
                    return [
                        'id' => $type->id,  
                        'name' => $type->getTranslation('name', $lang),
                        'commission' => $type->commission,
                    ];
                }),
                'message' => 'Provider Types retrieved successfully',
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
