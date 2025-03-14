<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PaymentMethodDataTable;
use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index(PaymentMethodDataTable $dataTable)
    {
        return $dataTable->render('admins.payment-methods.index');
    }


    public function create()
    {
        return view('admins.payment-methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'order' => 'required|integer',
            'default' => 'required',
            'route' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $payment_method = PaymentMethod::create([
            'name' => $request->name,
            'description' => $request->description,
            'default' => $request->default,
            'order' => $request->order,
            'route' => $request->route,
        ]);

        // Upload image and associate with payment method
        if ($request->hasFile('image')) {
            $path = $payment_method->uploadImage($request->image, 'uploads');
            $payment_method->image()->create(['path' => $path]);
        }

        return redirect()->route('admin.payment-methods.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.payment_method')]));
    }

    public function edit($id)
    {
        $payment_method = PaymentMethod::findOrFail($id);
        return view('admins.payment-methods.edit', compact('payment_method'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'order' => 'required|integer',
            'default' => 'required',
            'route' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $payment_method = PaymentMethod::findOrFail($id);
        $payment_method->update([
            'name' => $request->name,
            'description' => $request->description,
            'default' => $request->default,
            'order' => $request->order,
            'route' => $request->route,
        ]);

        // Upload image and associate with payment method
        if ($request->hasFile('image')) {
            $path = $payment_method->uploadImage($request->image, 'uploads');
            $payment_method->image()->delete();
            $payment_method->image()->create(['path' => $path]);
        }

        return redirect()->route('admin.payment-methods.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.payment_method')]));
    }

    public function destroy($id)
    {
        $payment_method = PaymentMethod::findOrFail($id);
        
        if ($payment_method->payments()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => __('lang.cannot_delete_has_children', ['operator' => __('lang.payment')]),
            ]);
        }

        $payment_method->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.payment_method')])
        ], 200);
    }
}
