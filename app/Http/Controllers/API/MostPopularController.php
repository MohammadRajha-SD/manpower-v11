<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MostPopular;
use Illuminate\Http\Request;

class MostPopularController extends Controller
{
    public function index()
    {
        $mps = MostPopular::with(['category', 'image'])->get();

        $mapped = $mps->map(function ($mp) {
            return [
                'id' => $mp->id,
                'name' => $mp->name,
                'desc' => $mp->desc,
                'category' => [
                    'id' => $mp->category?->id,
                    'name' => $mp->category?->name,
                    'desc' => $mp->category?->desc,
                ],
                'image' => $mp->image ? asset($mp->image->path) : null,
                'created_at' => $mp->created_at->toDateTimeString(),
            ];
        });
        
        return response()->json($mapped, 200);
    }
}
