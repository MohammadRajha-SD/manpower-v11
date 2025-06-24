<?php

namespace App\Http\Controllers\AAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    public function index(Request $request, $id)
    {
        $service = Service::with('provider.image', 'images')->where('id', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Service details fetched successfully',
            'data' => [
                'id' => $service->id,
                'name' => $service->name,
                'price' => $service->price,
                'description' => $service->description ?? '',
                'discount' => $service->discount > 0 ? (string) $service->discount : '0.00',
                'duration' => Carbon::parse($service->duration)->format('H:i'),
                'images' => $service->images
                    ? $service->images->map(function ($img) {
                        return asset('uploads/' . $img->path);
                    })->toArray()
                    : [],
                'provider' => [
                    'id' => $service->provider?->id,
                    'name' => $service->provider?->name,
                    'email' => $service->provider?->email,
                    'image' => $service->provider?->image ? $service->provider?->image?->path : asset('images/avatar_default.png'),
                ],
            ],
        ]);
    }
}
