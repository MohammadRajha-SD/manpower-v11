<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BookingDataTable;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\PaymentStatus;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Refund;

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
        $booking->payment->delete();
        $booking->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.booking')])
        ], 200);
    }
    public function cancelBooking(Request $request)
    {
        // Validate that payment_id is provided
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::findOrFail($request->booking_id);
        $payment = $booking->payment;

        // Check if the booking is already cancelled or completed
        if ($booking->booking_status->status == 'Cancelled' || $booking->payment->payment_status->status == 'Refunded') {
            return redirect()->route('admin.bookings.index')->with('warning', 'The booking is already cancelled or completed');
        }

        // Set your Stripe secret key
        Stripe::setApiKey(config('services.stripe.secret'));

        try {

            // Create the refund
            $refund = Refund::create([
                'payment_intent' => $payment->stripe_payment_id,
            ]);


            $paymentStatus = PaymentStatus::where('status', 'Refunded')->first();
            if ($paymentStatus) {
                $booking->payment->update([
                    'payment_status_id' => $paymentStatus->id,
                ]);
            }

            $bookingStatus = BookingStatus::where('status', 'Cancelled')->first();

            $booking->update([
                'booking_status_id' => $bookingStatus->id, // Ensure 'Refunded' status exists
            ]);

            return redirect()->route('admin.bookings.index')->with('success', 'Booking has been cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.bookings.index')->with('error', $e->getMessage());
        }
    }
}
