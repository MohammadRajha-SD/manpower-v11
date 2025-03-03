<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BookingStatusDataTable;
use App\Http\Controllers\Controller;
use App\Models\BookingStatus;
use Illuminate\Http\Request;

class BookingStatusController extends Controller
{
    public function index(BookingStatusDataTable $dataTable)
    {
        return $dataTable->render('admins.booking-statuses.index');
    }
    public function create()
    {
        return view('admins.booking-statuses.create');
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'order' => 'required',
            'status' => 'required',
        ]);

        BookingStatus::create($data);

        return redirect()->route('admin.booking-statuses.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.booking_status')]));
    }

    public function edit($id)
    {
        $booking_status = BookingStatus::findOrFail($id);

        return view('admins.booking-statuses.edit', compact('booking_status'));
    }

    public function update(Request $request, $id)
    {
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
