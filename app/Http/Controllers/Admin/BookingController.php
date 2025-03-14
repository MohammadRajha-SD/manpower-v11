<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BookingDataTable;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Booking;
use App\Models\BookingStatus;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(BookingDataTable $dataTable)
    {
        return $dataTable->render('admins.bookings.index');
    }

    public function edit($id)
    {
        $booking= Booking::findOrFail($id);
        $booking_statuses = BookingStatus::all();
        $addresses = Address::all();
        $payment_statuses = [];

        dd($booking);

        return view('admins.bookings.edit', compact('booking', 'booking_statuses','addresses'));
    }

    // TODO:
    public function update(Request $request, $id)
    {

        dd($request->all());
        $data = $this->validate($request, [
            'order' => 'required',
            'status' => 'required',
        ]);


        BookingStatus::findOrFail($id)->update($data);

        return redirect()->route('admin.booking-statuses.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.booking_status')]));
    }

    public function destroy($id)
    {
        $booking_status = BookingStatus::findOrFail($id);

        // Check if the address is linked to any providers
        if ($booking_status->bookings()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => __('lang.cannot_delete_has_children' ,['operator' => __('lang.booking')]),
            ]);
        }

        $booking_status->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.booking_status')])
        ], 200);
    }
}
