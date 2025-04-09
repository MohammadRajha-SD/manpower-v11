<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\Coupon;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\Service;
use App\Models\Tax;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function checkCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|exists:coupons,code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon->enabled) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon is not active.',
            ], 400);
        }

        if (Carbon::parse($coupon->expires_at)->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon has expired.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Coupon is valid.',
            'data' => [
                'code' => $coupon->code,
                'discount' => $coupon->discount,
                'discount_type' => $coupon->discount_type,
                'expires_at' => $coupon->expires_at,
            ]
        ]);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
            'service_id' => 'required|exists:services,id',
            'user_id' => 'required|exists:users,id',
            'address' => 'nullable',
            'coupon' => 'nullable|string',
            'hint' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $coupon = null;
        $discountAmount = 0;

        $service = Service::findOrFail($request->service_id);

        $price = $service->price;

        if (!empty($request->coupon)) {
            $coupon = Coupon::where('code', $request->coupon)
                ->where('enabled', 1)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if (!$coupon) {
                return response()->json([
                    'message' => 'Invalid or expired coupon.',
                ], 422);
            }

            $subtotal = $price * $validated['quantity'];

            if ($coupon->discount_type === 'percent') {
                $discountAmount = ($subtotal * $coupon->discount) / 100;
            } else {
                $discountAmount = $coupon->discount;
            }
        }

        // $taxAmount = 0;

        // if ($request->tax_id) {
        //     $tax = Tax::find($request->tax_id);
        //     if ($tax) {
        //         $subtotal = $price * $validated['quantity'];
        //         $taxAmount = ($subtotal - $discountAmount) * ($tax->rate / 100);
        //     }
        // }


        $booking_status = BookingStatus::where('status', 'Pending')->orWhere('order', 1)->first();
        $payment_status = PaymentStatus::where('status', 'Pending')->orWhere('order', 1)->first();
        $payment_method = PaymentMethod::where('name', 'stripe')->orwhere('order', 1)->first();

        $booking = new Booking();
        $booking->quantity = $request->quantity;
        $booking->service_id = $request->service_id;
        $booking->user_id = $request->user_id;
        $booking->address = $request->address;
        $booking->booking_status_id = $booking_status->id;
        $booking->hint = $request->hint;
        $booking->coupon = $request->coupon;
        $booking->booking_at = now();

        $payment = Payment::create([
            'amount' => $discountAmount,
            'description' => 'Transaction for Booking #' . $booking->id,
            'user_id' => $request->user_id,
            'payment_status_id' => $payment_status->id,
            'payment_method_id' => $payment_method->id,
        ]);

        $booking->payment_id = $payment->id;
        $booking->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Booking created successfully.',
            'booking' => $booking,
            // 'total' => round(($price * $validated['quantity']) - $discountAmount + $taxAmount, 2)
        ]);
    }
}
