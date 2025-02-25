<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CouponDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Provider;
use App\Models\Coupon;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function index(CouponDataTable $dataTable)
    {
        return $dataTable->render('admins.coupons.index');
    }
    public function create()
    {
        $services = Service::all();
        $providers = Provider::all();
        $categories = Category::all();
        return view(
            'admins.coupons.create',
            compact('categories', 'providers', 'services')
        );
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $validated = $request->validate([
            'code' => 'required|unique:coupons,code|max:255',
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percent,fixed',
            'description' => 'nullable',
            'expires_at' => 'required|date|after:today',
            'enabled' => 'nullable|boolean',

            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',

            'providers' => 'nullable|array',
            'providers.*' => 'exists:providers,id',

            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        try {
            $coupon = Coupon::create([
                'code' => $validated['code'],
                'discount' => $validated['discount'],
                'discount_type' => $validated['discount_type'],
                'description' => $validated['description'],
                'expires_at' => $validated['expires_at'],
                'enabled' => $validated['enabled'] ?? 0,
            ]);

            // Attach related models (services, providers, categories) to the coupon in the discountables table
            if (isset($validated['services'])) {
                foreach ($validated['services'] as $service) {
                    $coupon->discountables()->create(['coupon_id' => $coupon->id, 'discountable_id' => $service, 'discountable_type' => Service::class]);
                }
            }
            if (isset($validated['categories'])) {
                foreach ($validated['categories'] as $category) {
                    $coupon->discountables()->create(['coupon_id' => $coupon->id, 'discountable_id' => $category, 'discountable_type' => Category::class]);
                }
            }

            if (isset($validated['providers'])) {
                foreach ($validated['providers'] as $provider) {
                    $coupon->discountables()->create(['coupon_id' => $coupon->id, 'discountable_id' => $provider, 'discountable_type' => Provider::class]);
                }
            }

            DB::commit();

            return redirect()->route('admin.coupons.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.coupon')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $services = Service::all();
        $providers = Provider::all();
        $categories = Category::all();
        return view(
            'admins.coupons.edit',
            compact('categories', 'providers', 'services', 'coupon')
        );
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $validated = $request->validate([
            'code' => 'required|max:255|unique:coupons,code,' . $id,
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percent,fixed',
            'description' => 'nullable',
            'expires_at' => 'required|date|after:today',
            'enabled' => 'nullable|boolean',

            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',

            'providers' => 'nullable|array',
            'providers.*' => 'exists:providers,id',

            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        try {
            $coupon = Coupon::findOrFail($id);

            // Update the coupon details
            $coupon->update([
                'code' => $validated['code'],
                'discount' => $validated['discount'],
                'discount_type' => $validated['discount_type'],
                'description' => $validated['description'],
                'expires_at' => $validated['expires_at'],
                'enabled' => $validated['enabled'] ?? 0,
            ]);

            // Detach the old related models (services, providers, categories) from the coupon
            $coupon->discountables()->delete();

            if (isset($validated['services'])) {
                foreach ($validated['services'] as $service) {
                    $coupon->discountables()->create(['coupon_id' => $coupon->id, 'discountable_id' => $service, 'discountable_type' => Service::class]);
                }
            }

            if (isset($validated['categories'])) {
                foreach ($validated['categories'] as $category) {
                    $coupon->discountables()->create(['coupon_id' => $coupon->id, 'discountable_id' => $category, 'discountable_type' => Category::class]);
                }
            }

            if (isset($validated['providers'])) {
                foreach ($validated['providers'] as $provider) {
                    $coupon->discountables()->create(['coupon_id' => $coupon->id, 'discountable_id' => $provider, 'discountable_type' => Provider::class]);
                }
            }

            DB::commit();

            return redirect()->route('admin.coupons.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.coupon')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->discountables()->delete();
            $coupon->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.coupon')])
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
