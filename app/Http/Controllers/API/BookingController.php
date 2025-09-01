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
use Illuminate\Support\Facades\Log;

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

    $service = Service::findOrFail($request->service_id);

    // relations
    $couponServices   = $coupon->services()->pluck('id')->toArray();
    $couponCategories = $coupon->categories()->pluck('id')->toArray();
    $couponProviders  = $coupon->providers()->pluck('id')->toArray();

    $isGlobalCoupon = empty($couponServices) && empty($couponCategories) && empty($couponProviders);

    $isApplicable = false;

    if ($isGlobalCoupon) {
        $isApplicable = true;
    } elseif (in_array($service->id, $couponServices)) {
        $isApplicable = true;
    } elseif (in_array($service->category_id, $couponCategories)) {
        $isApplicable = true;
    } elseif (in_array($service->provider_id, $couponProviders)) {
        $isApplicable = true;
    }

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

    public function checkCouponOld(Request $request)
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
        'services' => 'required|array|min:1',
        'services.*.id' => 'required|exists:services,id',
        'user_id' => 'required|exists:users,id',
        'address' => 'required|string',
        'emirate' => 'required|string',
        'coupon' => 'nullable|string',
        'hint' => 'nullable|string',
        'num_cleaner' => 'nullable|integer|min:1',
        'stay_hours' => 'nullable|integer|min:1',
        'cleaning_times' => 'nullable|in:once,weekly,multiple',
        'cleaning_need' => 'nullable|boolean',
        'special_instructions' => 'nullable|string',
        'start_at' => 'nullable|date',
        'ends_at' => 'nullable|date|after_or_equal:start_at',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $validated = $validator->validated();

    $discountAmount = 0;
    $serviceCharge = 0;
    $totalAmount   = 0;

    $servicesInBooking = [];

    // calculate subtotal for each service
    foreach ($validated['services'] as $srv) {
        $service = Service::findOrFail($srv['id']);

        $selectedAddress = $service->addresses()
            ->where('address', $validated['emirate'])
            ->first();

        $charge = $selectedAddress?->service_charge ?? 0;
        $serviceCharge += $charge;

        $price = $service->discount_price > 0 ? $service->discount_price : $service->price;
        $subtotal = $price;

        $totalAmount += $subtotal;
        $servicesInBooking[] = $service;
    }

    $totalAmount += $serviceCharge;

    /**
     * ============================
     * Coupon validation & discount
     * ============================
     */
    if (!empty($validated['coupon'])) {
        $coupon = Coupon::where('code', $validated['coupon'])
            ->where('enabled', 1)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$coupon) {
            return response()->json(['message' => 'Invalid or expired coupon.'], 422);
        }

        // get coupon relations
        $couponServices   = $coupon->services()->pluck('id')->toArray();
        $couponCategories = $coupon->categories()->pluck('id')->toArray();
        $couponProviders  = $coupon->providers()->pluck('id')->toArray();

        $isGlobalCoupon = empty($couponServices) && empty($couponCategories) && empty($couponProviders);

        if ($isGlobalCoupon) {
            // apply to total
            $discountAmount = $coupon->discount_type === 'percent'
                ? ($totalAmount * $coupon->discount) / 100
                : $coupon->discount;
        } else {
            // apply only to eligible services
            foreach ($servicesInBooking as $service) {
                $price = $service->discount_price > 0 ? $service->discount_price : $service->price;

                $eligible = false;

                if (in_array($service->id, $couponServices)) {
                    $eligible = true;
                }

                if (!$eligible && in_array($service->category_id, $couponCategories)) {
                    $eligible = true;
                }

                if (!$eligible && in_array($service->provider_id, $couponProviders)) {
                    $eligible = true;
                }

                if ($eligible) {
                    if ($coupon->discount_type === 'percent') {
                        $discountAmount += ($price * $coupon->discount) / 100;
                    } else {
                        $discountAmount += $coupon->discount; // per eligible service
                    }
                }
            }
        }

        $totalAmount -= $discountAmount;
        if ($totalAmount < 0) {
            $totalAmount = 0; // prevent negative total
        }
    }

    /**
     * ============================
     * Statuses
     * ============================
     */
    $booking_status = BookingStatus::where('status', 'Pending')->orWhere('order', 1)->first();
    $payment_status = PaymentStatus::where('status', 'Pending')->orWhere('order', 1)->first();
    $payment_method = PaymentMethod::where('name', 'stripe')->orWhere('order', 1)->first();

    /**
     * ============================
     * Booking
     * ============================
     */
    $booking = Booking::create([
        'quantity' => $validated['quantity'],
        'user_id' => $validated['user_id'],
        'address' => $validated['address'],
        'emirate' => $validated['emirate'],
        'booking_status_id' => $booking_status->id,
        'hint' => $validated['hint'] ?? null,
        'coupon' => $validated['coupon'] ?? null,
        'start_at' => $validated['start_at'] ?? null,
        'ends_at' => $validated['ends_at'] ?? null,
        'num_cleaner' => $validated['num_cleaner'] ?? null,
        'stay_hours' => $validated['stay_hours'] ?? null,
        'cleaning_times' => $validated['cleaning_times'] ?? null,
        'cleaning_need' => $validated['cleaning_need'] ?? 0,
        'special_insteuctions' => $validated['special_instructions'] ?? null,
        'booking_at' => now(),
    ]);

    // attach services to pivot
    $pivotData = [];
    foreach ($servicesInBooking as $service) {
        $price = $service->discount_price > 0 ? $service->discount_price : $service->price;
        $pivotData[$service->id] = ['price' => $price];
    }
    $booking->services()->sync($pivotData);

    /**
     * ============================
     * Payment
     * ============================
     */
    $payment = Payment::create([
        'amount' => $totalAmount,
        'description' => 'Transaction for Booking #' . $booking->id,
        'user_id' => $validated['user_id'],
        'payment_status_id' => $payment_status->id,
        'payment_method_id' => $payment_method->id,
    ]);

    // Stripe checkout
    Stripe::setApiKey(config('services.stripe.secret'));
    $checkoutSession = StripeSession::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'aed',
                'product_data' => [
                    'name' => 'Service Booking #' . $booking->id,
                ],
                'unit_amount' => $totalAmount * 100,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => route('booking.payment.success', ['booking_id' => $booking->id]),
        'cancel_url' => route('booking.payment.cancel', ['booking_id' => $booking->id]),
    ]);

    $payment->update([
        'stripe_payment_id' => $checkoutSession->id,
        'stripe_payment_link' => $checkoutSession->url,
    ]);

    $booking->update(['payment_id' => $payment->id]);

    return response()->json([
        'status' => 'success',
        'message' => 'Booking created successfully.',
        'booking' => $booking->load('services'),
        'payment_url' => $checkoutSession->url,
        'discount' => $discountAmount,
        'final_total' => $totalAmount,
    ]);
}


    public function storeOld(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
            'services' => 'required|array|min:1',
            'services.*.id' => 'required|exists:services,id',
            // 'services.*.quantity' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string',
            'emirate' => 'required|string',
            'coupon' => 'nullable|string',
            'hint' => 'nullable|string',
            'num_cleaner' => 'nullable|integer|min:1',
            'stay_hours' => 'nullable|integer|min:1',
            'cleaning_times' => 'nullable|in:once,weekly,multiple',
            'cleaning_need' => 'nullable|boolean',
            'special_instructions' => 'nullable|string',
            'start_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $discountAmount = 0;
        $serviceCharge = 0;
        $totalAmount = 0;

        // calculate total across all services
        foreach ($validated['services'] as $srv) {
            $service = Service::findOrFail($srv['id']);

            $selectedAddress = $service->addresses()
                ->where('address', $validated['emirate'])
                ->first();

            $charge = $selectedAddress?->service_charge ?? 0;
            $serviceCharge += $charge;

            $price = $service->discount_price > 0 ? $service->discount_price : $service->price;
            $subtotal = $price ;

            $totalAmount += $subtotal;
        }

        $totalAmount += $serviceCharge;

        // coupon check
        if (!empty($validated['coupon'])) {
            $coupon = Coupon::where('code', $validated['coupon'])
                ->where('enabled', 1)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if (!$coupon) {
                return response()->json(['message' => 'Invalid or expired coupon.'], 422);
            }

            if ($coupon->discount_type === 'percent') {
                $discountAmount = ($totalAmount * $coupon->discount) / 100;
            } else {
                $discountAmount = $coupon->discount;
            }

            $totalAmount -= $discountAmount;
        }

        // statuses
        $booking_status = BookingStatus::where('status', 'Pending')
            ->orWhere('order', 1)->first();
        $payment_status = PaymentStatus::where('status', 'Pending')
            ->orWhere('order', 1)->first();
        $payment_method = PaymentMethod::where('name', 'stripe')
            ->orWhere('order', 1)->first();

        // create booking
        $booking = Booking::create([
            'quantity' => $validated['quantity'],
            'user_id' => $validated['user_id'],
            'address' => $validated['address'],
            'emirate' => $validated['emirate'],
            'booking_status_id' => $booking_status->id,
            'hint' => $validated['hint'] ?? null,
            'coupon' => $validated['coupon'] ?? null,
            'start_at' => $validated['start_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
            'num_cleaner' => $validated['num_cleaner'] ?? null,
            'stay_hours' => $validated['stay_hours'] ?? null,
            'cleaning_times' => $validated['cleaning_times'] ?? null,
            'cleaning_need' => $validated['cleaning_need'] ?? 0,
            'special_insteuctions' => $validated['special_instructions'] ?? null,
            'booking_at' => now(),
        ]);

        // attach services to pivot
        $pivotData = [];

        foreach ($validated['services'] as $srv) {
            $service = Service::find($srv['id']);
            $price = $service->discount_price > 0 ? $service->discount_price : $service->price;

            $pivotData[$srv['id']] = [
                'price' => $price,
            ];
        }

        $booking->services()->sync($pivotData);

        // create payment
        $payment = Payment::create([
            'amount' => $totalAmount,
            'description' => 'Transaction for Booking #' . $booking->id,
            'user_id' => $validated['user_id'],
            'payment_status_id' => $payment_status->id,
            'payment_method_id' => $payment_method->id,
        ]);

        // Stripe checkout
        Stripe::setApiKey(config('services.stripe.secret'));

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

        $payment->update([
            'stripe_payment_id' => $checkoutSession->id,
            'stripe_payment_link' => $checkoutSession->url,
        ]);

        $booking->update(['payment_id' => $payment->id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Booking created successfully.',
            'booking' => $booking->load('services'),
            'payment_url' => $checkoutSession->url,
        ]);
    }

    public function success(Request $request)
    {
        $booking = Booking::with('user', 'payment')->findOrFail($request->booking_id);

        Stripe::setApiKey(config('services.stripe.secret'));

        $payment = $booking->payment;
        $user = $booking->user;

        $session = StripeSession::retrieve($payment->stripe_payment_id);

        if ($session->payment_status == 'paid') {
            $payment->update([
                'payment_status_id' => 2,
                'stripe_payment_id' => $session->payment_intent,
            ]);
            try {
                if ($request->lang === 'ar') {
                    Mail::to($user->email)
                        ->send(new SendPaymentLinkAR($user, $payment->stripe_payment_link));
                } else {
                    Mail::to($user->email)
                        ->send(new SendPaymentLink($user, $payment->stripe_payment_link));
                }
            } catch (\Exception $e) {
                // Log or handle exception
            }
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

    public function cancelBooking2(Request $request)
    {


        $request->validate([
            'bookingId' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::findOrFail($request->bookingId);

        // Check if the booking is already cancelled or completed
        if ($booking->booking_status->status == 'Cancelled' || $booking->payment->payment_status->status == 'Refunded') {
            return response()->json([
                'status' => 'error',
                'message_en' => 'The booking is already cancelled or completed.',
                'message_ar' => 'تم إلغاء الحجز أو إكماله بالفعل',
            ], 404);
        }


        try {
            // $paymentStatus = PaymentStatus::where('status', 'Refunded')->first();

            // if ($paymentStatus) {
            //     $booking->payment->update([
            //         'payment_status_id' => $paymentStatus->id,
            //     ]);
            // }

            $bookingStatus = BookingStatus::where('status', 'Cancelled')->first();

            $booking->update([
                'booking_status_id' => $bookingStatus->id,
                'cancel' => 1,
            ]);
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
