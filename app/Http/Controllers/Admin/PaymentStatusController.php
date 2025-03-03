<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PaymentStatusDataTable;
use App\Http\Controllers\Controller;
use App\Models\PaymentStatus;
use Illuminate\Http\Request;

class PaymentStatusController extends Controller
{
    public function index(PaymentStatusDataTable $dataTable)
    {
        return $dataTable->render('admins.payment-statuses.index');
    }
    public function create()
    {
        return view('admins.payment-statuses.create');
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'order' => 'required',
            'status' => 'required',
        ]);

        PaymentStatus::create($data);

        return redirect()->route('admin.payment-statuses.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.payment_status')]));
    }

    public function edit($id)
    {
        $payment_status = PaymentStatus::findOrFail($id);

        return view('admins.payment-statuses.edit', compact('payment_status'));
    }

    public function update(Request $request, $id)
    {
        $data = $this->validate($request, [
            'order' => 'required',
            'status' => 'required',
        ]);


        PaymentStatus::findOrFail($id)->update($data);

        return redirect()->route('admin.payment-statuses.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.payment_status')]));
    }

    public function destroy($id)
    {
        $payment_status = PaymentStatus::findOrFail($id);

        // Check if the address is linked to any providers
        // if ($booking_status->bookings()->exists()) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => __('lang.cannot_delete_has_children' ,['operator' => __('lang.payment')]),
        //     ]);
        // }

        $payment_status->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.payment_status')])
        ], 200);
    }
}
