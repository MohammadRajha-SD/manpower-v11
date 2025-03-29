<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        try {
            $currencies = Currency::all();

            return response()->json([
                'status' => 'success',
                'currencies' => $currencies,
                'message' => 'Currencies retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again.',
                'currencies' => [],
            ], 500);
        }
    }
}
