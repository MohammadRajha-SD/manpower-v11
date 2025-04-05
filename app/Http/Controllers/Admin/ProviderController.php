<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProviderDataTable;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Provider;
use App\Models\ProviderType;
use App\Models\Tax;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{
    public function index(ProviderDataTable $dataTable)
    {
        return $dataTable->render('admins.providers.providers.index');
    }

    public function create()
    {
        $users = User::where('is_admin', 0)->get();
        $provider_types = ProviderType::all();
        $addresses = Address::all();
        $taxes = Tax::all();

        return view('admins.providers.providers.create', compact('users', 'provider_types', 'addresses', 'taxes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|unique:providers,email',
            'description' => 'required|string',
            'password' => 'required|min:6',
            'phone_number' => 'required|string|max:20',
            'mobile_number' => 'required|string|max:20',

            'provider_type_id' => 'required|integer|exists:provider_types,id',
            'availability_range' => 'required|numeric|min:0',

            'accepted' => 'nullable|boolean',
            'available' => 'nullable|boolean',
            'featured' => 'nullable|boolean',

            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
            'addresses' => 'nullable|array',
            'addresses.*' => 'exists:addresses,id',
            'taxes' => 'nullable|array',
            'taxes.*' => 'exists:taxes,id',

            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Store provider
            $provider = Provider::create([
                'name' => $request->input('name'),
                'password' => bcrypt($request->input('password')),
                'email' => $request->input('email'),
                'provider_type_id' => $request->input('provider_type_id'),
                'description' => $request->input('description'),
                'phone_number' => $request->input('phone_number'),
                'mobile_number' => $request->input('mobile_number'),
                'availability_range' => $request->input('availability_range'),
                'accepted' => $request->boolean('accepted'),
                'available' => $request->boolean('available'),
                'featured' => $request->boolean('featured'),
            ]);

            // Attach addresses if provided
            if ($request->has('addresses')) {
                $provider->addresses()->attach($request->input('addresses'));
            }
            // Attach users if provided
            if ($request->has('users')) {
                $provider->users()->attach($request->input('users'));
            }

            // Attach taxes if provided
            if ($request->has('taxes')) {
                $provider->taxes()->attach($request->input('taxes'));
            }

            // Upload images and associate with category
            if ($request->hasFile('images')) {
                $paths = $provider->uploadMultiImage($request->images, 'uploads');

                foreach ($paths as $path) {
                    $provider->images()->create(['path' => $path]);
                }
            }
            
            DB::commit();

            return redirect()->route('admin.providers.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.e_provider')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating provider: ' . $e->getMessage());
        }
    }


    public function edit($id)
    {
        $provider = Provider::findOrFail($id);
        $users = User::where('is_admin', 0)->get();
        $provider_types = ProviderType::all();
        $addresses = Address::all();
        $taxes = Tax::all();

        return view('admins.providers.providers.edit', compact('users', 'provider_types', 'addresses', 'taxes', 'provider'));
    }

    public function update(Request $request, Provider $provider)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|unique:providers,email,' . $provider->id,

            'phone_number' => 'required|string|max:20',
            'mobile_number' => 'required|string|max:20',

            'provider_type_id' => 'required|integer|exists:provider_types,id',
            'availability_range' => 'required|numeric|min:0',

            'accepted' => 'nullable|boolean',
            'available' => 'nullable|boolean',
            'featured' => 'nullable|boolean',

            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
            'addresses' => 'nullable|array',
            'addresses.*' => 'exists:addresses,id',
            'taxes' => 'nullable|array',
            'taxes.*' => 'exists:taxes,id',

            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Update provider details
            $provider->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'provider_type_id' => $request->input('provider_type_id'),
                'description' => $request->input('description'),
                'phone_number' => $request->input('phone_number'),
                'mobile_number' => $request->input('mobile_number'),
                'availability_range' => $request->input('availability_range'),
                'accepted' => $request->boolean('accepted'),
                'available' => $request->boolean('available'),
                'featured' => $request->boolean('featured'),
            ]);

            // Sync addresses if provided
            if ($request->has('addresses')) {
                $provider->addresses()->sync($request->input('addresses'));
            }

            // Sync users if provided
            if ($request->has('users')) {
                $provider->users()->sync($request->input('users'));
            }

            // Sync taxes if provided
            if ($request->has('taxes')) {
                $provider->taxes()->sync($request->input('taxes'));
            }

            // Handle images update
            if ($request->hasFile('images')) {
                $paths = $provider->uploadMultiImage($request->images, 'uploads');

                foreach ($paths as $path) {
                    $provider->images()->create(['path' => $path]);
                }
            }

            DB::commit();

            return redirect()->route('admin.providers.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.e_provider')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating provider: ' . $e->getMessage());
        }
    }

    public function destroy(Provider $provider)
    {
        try {
            DB::beginTransaction();

            if ($provider->schedules()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('lang.cannot_delete_has_children', ['operator' => __('lang.e_provider')]),
                ]);
            }
            // Detach related records to avoid foreign key constraints
            $provider->addresses()->detach();
            $provider->users()->detach();
            $provider->taxes()->detach();

            // Check if provider has images before deleting
            if ($provider->images()->exists()) {
                // Delete related images
                foreach ($provider->images as $image) {
                    $image->delete();
                    $provider->deleteImage($image->path);
                }
            }

            $provider->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.e_provider')])
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting provider: ' . $e->getMessage());
        }
    }
}
