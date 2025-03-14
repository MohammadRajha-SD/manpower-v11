<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PackDataTable;
use App\Http\Controllers\Controller;
use App\Models\Pack;
use Illuminate\Http\Request;
use Stripe\Product;
use Stripe\Price;
use Stripe\Stripe;
class PackController extends Controller
{
    public function index(PackDataTable $dataTable)
    {
        return $dataTable->render('admins.packs.index');
    }

    public function create()
    {
        return view('admins.packs.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'stripe_plan_id' => 'nullable|string',
            'type' => 'required',
            'price' => 'required|integer|min:0',
            'text' => 'required',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'number_of_months' => 'required|integer|min:1',
            'number_of_ads' => 'required|integer|min:0',
            'number_of_subcategories' => 'required|integer|min:0',
            'not_in_featured_services' => 'boolean',
            'not_on_image_slider' => 'boolean',
        ]);

        // Set the Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            if (!$validated['stripe_plan_id']) {
                $product = Product::create([
                    'name' => $validated['text'],
                    'description' => $validated['short_description'], 
                    'type' => 'service',
                ]);

                // Create a Price for the Product
                $price = Price::create([
                    'unit_amount' => $validated['price'] * 100,
                    'currency' => 'usd',  
                    'recurring' => ['interval' => 'month'], 
                    'product' => $product->id,
                ]);

                $validated['stripe_plan_id'] = $price->id;
            }

            $pack = new Pack($validated);
            $pack->save();

            // Redirect with success message
            return redirect()->route('admin.packs.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.pack')]));
        } catch (\Exception $e) {
            // Handle any errors and roll back
            return redirect()->back()->with('error', __('lang.something_went_wrong') . ' ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $pack = Pack::findOrFail($id);
        return view('admins.packs.edit', compact('pack'));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'stripe_plan_id' => 'required|string',
            'type' => 'required',
            'price' => 'required|integer|min:0',
            'text' => 'required',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'number_of_months' => 'required|integer|min:1',
            'number_of_ads' => 'required|integer|min:0',
            'number_of_subcategories' => 'required|integer|min:0',
            'not_in_featured_services' => 'boolean',
            'not_on_image_slider' => 'boolean',
        ]);

        $pack = Pack::findOrFail($id);
        $pack->update($validated);

        return redirect()->route('admin.packs.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.pack')]));
    }

    public function destroy($id)
    {
        $pack = Pack::findOrFail($id);

        if ($pack->subscriptions()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => __('lang.cannot_delete_has_children', ['operator' => __('lang.subscription')]),
            ]);
        }

        $pack->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.pack')])
        ], 200);
    }

}
