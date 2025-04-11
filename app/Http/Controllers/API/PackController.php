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

            $newPacks = $packs->map(function ($p) {
                return [
                    'id' => $p->id,
                    'type' => $p->type,
                    'price' => $p->price,
                    'text' => $p->text,
                    'short_description' => $p->short_description,
                    "number_of_months" => $p->number_of_months,
                    "number_of_ads" => $p->number_of_ads,
                    "number_of_subcategories" => $p->number_of_subcategories,
                    "not_in_featured_services" => $p->not_in_featured_services,
                    "not_on_image_slider" => $p->not_on_image_slider,
                ];
            });

            return response()->json([
                'status' => 'success',
                'packs' => $newPacks,
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
