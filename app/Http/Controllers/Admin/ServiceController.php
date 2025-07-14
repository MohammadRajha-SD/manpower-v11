<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ServiceDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceAddress;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    public function index(ServiceDataTable $dataTable)
    {
        return $dataTable->render('admins.services.index');
    }
    public function create()
    {
        $categories = Category::all();
        $providers = Provider::all();

        return view('admins.services.create', compact('categories', 'providers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'addresses' => 'required|array|min:1',
            'addresses.*' => 'required|string',
            'provider_id' => 'required|exists:providers,id',
            'name' => 'required',
            'description' => 'required',
            'discount_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'price_unit' => 'required|in:fixed,hourly',
            'quantity_unit' => 'nullable|integer|min:0',
            'duration' => 'required',
            'featured' => 'boolean',
            'enable_booking' => 'boolean',
            'available' => 'boolean',
            'terms' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $service = Service::create([
            'provider_id' => $request->provider_id,
            'address' => $request->addresses[0],
            'name' => $request->name,
            'description' => $request->description,
            'discount_price' => $request->discount_price,
            'price' => $request->price,
            'price_unit' => $request->price_unit,
            'quantity_unit' => $request->quantity_unit,
            'duration' => $request->duration,
            'featured' => $request->featured,
            'enable_booking' => $request->enable_booking,
            'available' => $request->available,
            'terms' => $request->terms,
            'category_id' => $request->category_id,
            'qty_limit' => $request->qty_limit,
        ]);


        foreach ($request->addresses as $address) {
            $service->addresses()->create([
                'address' => $address,
                'service_charge' => 0,
            ]);
        }

        // Upload images and associate with 'service'
        if ($request->hasFile('images')) {
            $paths = $service->uploadMultiImage($request->images, 'uploads');

            foreach ($paths as $path) {
                $service->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.services.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.e_service')]));
    }

    public function edit($id)
    {
        $service = Service::with('category', 'addresses')->findOrFail($id);
        $categories = Category::all();
        $providers = Provider::all();

        return view('admins.services.edit', compact('service', 'categories', 'providers'));
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'addresses' => 'required|array|min:1',
            'addresses.*' => 'required|string',
            'provider_id' => 'required|exists:providers,id',
            'name' => 'required',
            'description' => 'required',
            'discount_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'price_unit' => 'required|in:fixed,hourly',
            'quantity_unit' => 'nullable|integer|min:1',
            'duration' => 'required',
            'terms' => 'nullable',
            'featured' => 'boolean',
            'enable_booking' => 'boolean',
            'available' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $service = Service::findOrFail($id);

        $service->update([
            'provider_id' => $request->provider_id,
            'name' => $request->name,
            'description' => $request->description,
            'discount_price' => $request->discount_price,
            'price' => $request->price,
            'price_unit' => $request->price_unit,
            'quantity_unit' => $request->quantity_unit,
            'duration' => $request->duration,
            'featured' => $request->featured,
            'available' => $request->available,
            'enable_booking' => $request->enable_booking,
            'category_id' => $request->category_id,
            'address' => $request->addresses[0],
            'terms' => $request->terms,
            'qty_limit' => $request->qty_limit,
        ]);

        $existingAddressIds = [];

        foreach ($request->addresses as $addressSlug) {
            if (!$addressSlug)
                continue;

            // Check if the address already exists for this service
            $existing = $service->addresses()->where('address', $addressSlug)->first();

            if ($existing) {
                // Already exists, just add ID to keep
                $existingAddressIds[] = $existing->id;
            } else {
                // Create new
                $new = $service->addresses()->create([
                    'address' => $addressSlug,
                    'service_charge' => 0,
                ]);
                $existingAddressIds[] = $new->id;
            }
        }

        // Delete any old addresses not in the new list
        $service->addresses()->whereNotIn('id', $existingAddressIds)->delete();

        // Handle image uploads
        if ($request->hasFile('images')) {
            $paths = $service->uploadMultiImage($request->images, 'uploads');

            foreach ($paths as $path) {
                $service->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.services.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.e_service')]));
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        if ($service->reviews()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => __('lang.cannot_delete_has_children'),
            ]);
        }

        if ($service->bookings()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => __('lang.cannot_delete_has_children', ['operator' => __('lang.booking')]),
            ]);
        }

        $service->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.e_service')])
        ], 200);
    }
}
