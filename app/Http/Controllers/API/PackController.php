<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pack;
use Illuminate\Http\Request;

class PackController extends Controller
{
    public function index()
    {
        try {
            $packs = Pack::all();

            return response()->json([
                'status' => 'success',
                'packs' => $packs,
                'message' => 'Packs retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again.',
                'packs' => [],
            ], 500);
        }
    }
}
