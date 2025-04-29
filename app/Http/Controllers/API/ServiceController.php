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
            'categories',
            'images',
            'image',
        ]);

        // Execute the query to get the results
        $services = $query->get();

        $newServices = $services->map(function ($slide) {
            return [
                'id' => $slide->id,
                'address' => $slide->address,
                'category_id' => $slide->category_id,
                'provider_id' => $slide->provider_id,
                'name' => $slide->name,
                'description' => $slide->description,
                'discount_price' => $slide->discount_price,
                'price' => $slide->price,
                'quantity_unit' => $slide->quantity_unit,
                'duration' => $slide->duration,
                'featured' => $slide->featured,
                'enable_booking' => $slide->enable_booking,
                'available' => $slide->available,
                'bookings' => $slide->bookings?->filter(fn($b) => $b->booking_status_id != 4 ||  $b->booking_status_id != 3)?->map(function ($b) {
                    return [
                        'id' => $b->id,
                        'start_at'=> $b->start_at,
                        'ends_at'=> $b->ends_at,
                    ];
                }),
                'created_at' => $slide->created_at,
                'provider' => [
                    'name' => $slide->provider->name,
                    'schedules' => $slide->provider->getSchedules(),
                    'email' => $slide->provider->email,
                    'description' => $slide->provider->description,
                    'phone_number' => $slide->provider->phone_number,
                    'mobile_number' => $slide->provider->mobile_number,
                    'availability_range' => $slide->provider->availability_range,
                    'available' => $slide->provider->available,
                    'accepted' => $slide->provider->accepted,
                    'featured' => $slide->provider->featured,
                    'created_at' => $slide->provider->created_at,
                ],

                'images' => $slide->images->map(function ($img) {
                    return asset('uploads/' .$img->path);
                })->toArray(),
            ];
        });
        return response()->json([
            'status' => 'success',
            'message' => 'All Services retrieved successfully.',
            'services' => $newServices,
        ], 200);
    }
}
