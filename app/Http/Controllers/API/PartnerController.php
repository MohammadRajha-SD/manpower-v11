<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{

    public function index()
    {
        try {
            $partners = Partner::with('image')->where('enabled', 1)->get();
            $newPartners = $partners->map(function ($partner) {
                return [
                    'id' => $partner->id,
                    'title' => $partner->title,
                    'desc' => $partner->desc,
                    'enabled' => $partner->enabled,
                    'created_at' => $partner->created_at,
                    'image_path' => asset('uploads/' . $partner->image->path),
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $newPartners,
                'message' => 'Partners retrieved successfully',
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
