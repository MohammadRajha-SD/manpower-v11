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
                'success' => true,
                'data' => $packs->toArray(),
                'message' => 'Packs retrieved successfully',
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
