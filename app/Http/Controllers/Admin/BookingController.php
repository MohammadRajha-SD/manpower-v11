<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BookingDataTable;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\PaymentStatus;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(BookingDataTable $dataTable)
    {
        return $dataTable->render('admins.bookings.index');
    }

    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        $booking_statuses = BookingStatus::all();
        $payment_statuses = PaymentStatus::all();

        $addresses = config('emirates');

        return view('admins.bookings.edit', compact('booking', 'booking_statuses', 'addresses', 'payment_statuses'));
    }

    public function update(Request $request, $id)
    {
        $data = $this->validate($request, [
            'booking_status_id' => 'required',
            'address' => 'required',
            'hint' => 'required',
            'booking_at' => 'required|date',
            'start_at' => 'required|date',
            'ends_at' => 'required|date|',
            'cancel' => 'required',
        ]);

        Booking::findOrFail($id)->update([
            'booking_status_id' => $request->booking_status_id,
            'address' => $request->address,
            'hint' => $request->hint,
            'booking_at' => $request->booking_at,
            'start_at' => $request->start_at,
            'ends_at' => $request->ends_at,
            'cancel' => $request->cancel,
        ]);

        return redirect()->route('admin.bookings.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.booking')]));
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.booking')])
        ], 200);
    }
}
