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
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendPaymentLink;
use App\Mail\SendPaymentLinkAR;
use App\Mail\BookingCancelledMail;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;
use Stripe\Refund;

class BookingController extends Controller
{
    public function checkCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|exists:coupons,code',
            'service_id' => 'required|exists:services,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message_en' => 'Validation failed.',
                'message_ar' => 'فشل التحقق من البيانات.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon->enabled) {
            return response()->json([
                'success' => false,
                'message_en' => 'Coupon is not active.',
                'message_ar' => 'القسيمة غير مفعّلة.',
            ], 400);
        }

        if (Carbon::parse($coupon->expires_at)->isPast()) {
            return response()->json([
                'success' => false,
                'message_en' => 'Coupon has expired.',
                'message_ar' => 'انتهت صلاحية القسيمة.',
            ], 400);
        }

        $isApplicable = $coupon->services->contains($request->service_id);

        if (!$isApplicable) {
            return response()->json([
                'success' => false,
                'message_en' => 'This coupon is not valid for the selected service.',
                'message_ar' => 'هذه القسيمة غير صالحة للخدمة المحددة.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message_en' => 'Coupon is valid for this service.',
            'message_ar' => 'القسيمة صالحة لهذه الخدمة.',
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
            'address' => 'required',
            'emirate' => 'required',
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

        $selectedAddress = $service->addresses()->where('address', $request->emirate)->first();
        $serviceCharge = $selectedAddress?->service_charge ?? 0;

        $price = $service->discount_price > 0 ? $service->discount_price : $service->price;

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

        // $subtotal = $price * $validated['quantity'];
        $subtotal = ($price * $validated['quantity']) + $serviceCharge;

        $totalAmount = $subtotal - $discountAmount;

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
        $booking->emirate = $request->emirate;
        $booking->start_at = $request->start_at ?? null;
        $booking->ends_at = $request->ends_at ?? null;
        $booking->booking_at = now();

        $payment = Payment::create([
            'amount' => $totalAmount,
            'description' => 'Transaction for Booking #' . $booking->id,
            'user_id' => $request->user_id,
            'payment_status_id' => $payment_status->id,
            'payment_method_id' => $payment_method->id,
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $booking->save();

        $checkoutSession = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'aed',
                        'product_data' => [
                            'name' => 'Service Booking #' . $booking->id,
                        ],
                        'unit_amount' => $totalAmount * 100,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('booking.payment.success', ['booking_id' => $booking->id]),
            'cancel_url' => route('booking.payment.cancel', ['booking_id' => $booking->id]),
        ]);

        $payment->stripe_payment_id = $checkoutSession->id;
        $payment->stripe_payment_link = $checkoutSession->url;
        $payment->save();

        $booking->update([
            'payment_id' => $payment->id,
        ]);

        $user = User::find($request->user_id);
        
        try {
            if ($request->lang === 'ar') {
                Mail::to($user->email)
                    ->send(new SendPaymentLinkAR($user, $checkoutSession->url));
            } else {
                Mail::to($user->email)
                    ->send(new SendPaymentLink($user, $checkoutSession->url));
            }
        } catch (\Exception $e) {
            // Log or handle exception
        }


        return response()->json([
            'status' => 'success',
            'message' => 'Booking created and payment email sent.',
            'booking' => $booking,
            'payment_url' => $checkoutSession->url,
        ]);
    }

    public function success(Request $request)
    {
        $booking = Booking::findOrFail($request->booking_id);

        Stripe::setApiKey(config('services.stripe.secret'));

        $payment = $booking->payment;

        $session = StripeSession::retrieve($payment->stripe_payment_id);

        if ($session->payment_status == 'paid') {
            $payment->update([
                'payment_status_id' => 2,
                'stripe_payment_id' => $session->payment_intent,
            ]);

            return redirect(env('FRONTEND_URL'));
        } else {
            return redirect(env('FRONTEND_URL'));
        }
    }

    public function cancel(Request $request)
    {
        return redirect(env('FRONTEND_URL'));
    }

    public function cancelBooking(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::findOrFail($request->booking_id);
        $payment = $booking->payment;

        // Check if the booking is already cancelled or completed
        if ($booking->booking_status->status == 'Cancelled' || $booking->payment->payment_status->status == 'Refunded') {
            return response()->json([
                'status' => 'error',
                'message_en' => 'The booking is already cancelled or completed.',
                'message_ar' => 'تم إلغاء الحجز أو إكماله بالفعل',
            ], 404);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Check if the booking is already cancelled or completed
            if ($booking->payment->payment_status->status == 'Paid') {
                Refund::create([
                    'payment_intent' => $payment->stripe_payment_id,
                ]);
            }

            $paymentStatus = PaymentStatus::where('status', 'Refunded')->first();

            if ($paymentStatus) {
                $booking->payment->update([
                    'payment_status_id' => $paymentStatus->id,
                ]);
            }

            $bookingStatus = BookingStatus::where('status', 'Cancelled')->first();

            $booking->update([
                'booking_status_id' => $bookingStatus->id,
                'cancel' => 1,
            ]);

            // ✅ Send email to user with CC to provider and admin
            Mail::to($booking->user?->email)
                ->cc([$booking->service?->provider?->email, env('CC_EMAIL', 'noreply@hpower.ae')])
                ->send(new BookingCancelledMail($booking, $request->reason));

            return response()->json([
                'status' => 'success',
                'message_en' => 'Booking has been cancelled successfully.',
                'message_ar' => 'تم إلغاء الحجز بنجاح.',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message_en' => $e->getMessage(),
                'message_ar' => $e->getMessage(),
            ], 404);
        }
    }
}
