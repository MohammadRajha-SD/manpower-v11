<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ServiceDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Models\Service;

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
            'category_id' => 'nullable|exists:categories,id',
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $service = Service::create($validatedData);

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
        $service = Service::findOrFail($id);
        $categories = Category::all();
        $providers = Provider::all();

        return view('admins.services.edit', compact('service', 'categories', 'providers'));
    }
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validatedData = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'provider_id' => 'required|exists:providers,id',
            'name' => 'required',
            'description' => 'required',
            'discount_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'price_unit' => 'required|in:fixed,hourly',
            'quantity_unit' => 'nullable|integer|min:1',
            'duration' => 'required',
            'featured' => 'boolean',
            'enable_booking' => 'boolean',
            'available' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $service->update($validatedData);

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

        $service->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.e_service')])
        ], 200);
    }
}
