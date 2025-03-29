<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $query = Service::query();

        // Filter services where available and enable_booking are 1
        $query->where('available', 1);
        $query->where('enable_booking', 1);

        // Add condition to filter services where the provider's 'accepted' field is 1
        $query->whereHas('provider', function ($providerQuery) {
            $providerQuery->where('accepted', 1)
                ->where('available', 1);
        });

        // Load related models
        $query->with([
            'provider',
            'category',
            'images',
            'image',
        ]);

        // Execute the query to get the results
        $services = $query->get();

        return response()->json([
            'status' => 'success',
            'message' => 'All Services retrieved successfully.',
            'services' => $services,
        ], 200);
    }
}
