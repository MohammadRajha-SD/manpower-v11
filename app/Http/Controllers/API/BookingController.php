<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\BookingSuccessMail;
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
            'code' => 'required|string',
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

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message_en' => 'Coupon not found.',
                'message_ar' => 'القسيمة غير موجودة.',
            ], 404);
        }

        $check = $coupon->isValidForServices([$request->service_id]);

        if (!$check['valid']) {
            return response()->json([
                'success' => false,
                'message_en' => 'Coupon is not valid for this service.',
                'message_ar' => 'هذه القسيمة غير صالحة لهذه الخدمة.',
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
            'services' => 'required|array|min:1',
            'services.*.id' => 'required|exists:services,id',
            'services.*.quantity' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id',
            'address' => 'required',
            'emirate' => 'required',
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
        $servicesInBooking = [];

        /**
         * ============================
         * Calculate totals per service
         * ============================
         */
        foreach ($validated['services'] as $srv) {
            $service = Service::findOrFail($srv['id']);
            $lineQty = (int) $srv['quantity'];

            $selectedAddress = $service->addresses()
                ->where('address', $validated['emirate'])
                ->first();

            $charge = $selectedAddress?->service_charge ?? 0;
            $serviceCharge += $charge;

            $price = $service->discount_price > 0 ? $service->discount_price : $service->price;
            $subtotal = $price * $lineQty;

            $totalAmount += $subtotal;
            $servicesInBooking[] = [
                'model' => $service,
                'qty' => $lineQty,
                'unit_price' => $price,
            ];
        }

        // Add service charge (once, not per line unless intended)
        $totalAmount += $serviceCharge;

        /**
         * ============================
         * Coupon validation & discount
         * ============================
         */
        if (!empty($validated['coupon'])) {
            $coupon = Coupon::where('code', $validated['coupon'])->first();

            if (!$coupon) {
                return response()->json(['message' => 'Invalid or expired coupon.'], 422);
            }

            $serviceIds = collect($servicesInBooking)->pluck('model.id')->toArray();
            $check = $coupon->isValidForServices($serviceIds);

            if (!$check['valid']) {
                return response()->json(['message' => 'Coupon is not valid for selected services.'], 422);
            }

            // Load relations
            $couponServices = $coupon->services()->pluck('services.id')->toArray();
            $couponCategories = $coupon->categories()->pluck('categories.id')->toArray();
            $couponProviders = $coupon->providers()->pluck('providers.id')->toArray();

            $isGlobal = empty($couponServices) && empty($couponCategories) && empty($couponProviders);

            if ($isGlobal) {
                $discountAmount = $coupon->discount_type === 'percent'
                    ? ($totalAmount * $coupon->discount) / 100
                    : $coupon->discount;
            } else {
                foreach ($servicesInBooking as $row) {
                    $service = $row['model'];
                    $lineQty = $row['qty'];
                    $unit = $row['unit_price'];

                    $eligible = in_array($service->id, $couponServices)
                        || in_array($service->category_id, $couponCategories)
                        || in_array($service->provider_id, $couponProviders);

                    if ($eligible) {
                        $lineTotal = $unit * $lineQty;
                        $discountAmount += $coupon->discount_type === 'percent'
                            ? ($lineTotal * $coupon->discount) / 100
                            : $coupon->discount * $lineQty;
                    }
                }
            }

            $totalAmount -= $discountAmount;
            if ($totalAmount < 0)
                $totalAmount = 0;
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
        foreach ($servicesInBooking as $row) {
            $pivotData[$row['model']->id] = [
                'price' => $row['unit_price'],
                'quantity' => $row['qty'],
            ];
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
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'aed',
                        'product_data' => [
                            'name' => 'Service Booking #' . $booking->id,
                        ],
                        'unit_amount' => (int) round($totalAmount * 100),
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

        $booking = Booking::with('user', 'payment')->findOrFail($booking->id);
        $user = $booking->user;

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

        return response()->json([
            'status' => 'success',
            'message' => 'Booking created successfully.',
            'booking' => $booking->load('services'),
            'payment_url' => $checkoutSession->url,
            'discount' => $discountAmount,
            'final_total' => $totalAmount,
        ]);
    }

    public function success(Request $request)
    {
        $booking = Booking::with('user', 'payment', 'services')->findOrFail($request->booking_id);

        Stripe::setApiKey(config('services.stripe.secret'));

        $payment = $booking->payment;
        $user = $booking->user;

        $session = StripeSession::retrieve($payment->stripe_payment_id);

        if ($session->payment_status === 'paid') {
            // Update payment info
            $payment->update([
                'payment_status_id' => 2,
                'stripe_payment_id' => $session->payment_intent,
            ]);

            // Extract service names (comma separated)
            $serviceNames = $booking->services->pluck('name')->implode(', ');

            $providerNames = $booking->services
                ->pluck('provider.name')
                ->filter()
                ->unique()
                ->implode(', ');

            $providerEmails = $booking->services
                ->pluck('provider.email')
                ->filter()
                ->unique()
                ->implode(', ');

            $locale = $request->lang ?? 'en';

            // Static data array for email
            $data = [
                'serviceName' => $serviceNames,
                'userName' => $user->name,
                'userEmail' => $user->email,
                'userPhone' => $user->phone_number,
                'providerName' => $providerNames ?: 'N/A',
                'providerEmail' => $providerEmails ?: 'N/A',
                'quantity' => 1,
                'address' => $booking->address ?? 'N/A',
                'hint' => $booking->hint ?? 'N/A',
                'BookingData' => now()->locale($locale)->translatedFormat('d F Y'),
            ];

            // try {
            //     Mail::to($user->email)
            //         ->send(new BookingSuccessMail($data, $request->lang == 'ar' ? 'ar' : 'en'));
            // } catch (\Exception $e) {
            //     Log::error('Booking mail failed: ' . $e->getMessage());
            // }

            try {
                $firstProviderEmail = $booking->services
                    ->pluck('provider.email')
                    ->filter()
                    ->unique()
                    ->first();

                $mail = Mail::to($user->email);

                if ($firstProviderEmail) {
                    $mail->cc($firstProviderEmail);
                }

                $mail->send(new BookingSuccessMail(
                    $data,
                    $request->lang == 'ar' ? 'ar' : 'en'
                ));
            } catch (\Exception $e) {
                Log::error('Booking mail failed: ' . $e->getMessage());
            }


            return redirect(env('FRONTEND_URL'));
        }

        return redirect(env('FRONTEND_URL'));
    }


    public function successOld(Request $request)
    {
        $booking = Booking::with('user', 'payment', 'services')->findOrFail($request->booking_id);

        Stripe::setApiKey(config('services.stripe.secret'));

        $payment = $booking->payment;
        $user = $booking->user;

        $session = StripeSession::retrieve($payment->stripe_payment_id);

        if ($session->payment_status == 'paid') {
            $payment->update([
                'payment_status_id' => 2,
                'stripe_payment_id' => $session->payment_intent,
            ]);

            $data = [
                'serviceName' => $booking->services, // map the name of all services
                'userName' => $user->name,
                'userEmail' => $user->email,
                'userPhone' => $user->phone_number,
                'providerName' => $request->input('providerName'),
                'providerEmail' => $request->input('providerEmail'),
                'quantity' => $request->input('quantity'),
                'address' => $request->input('address'),
                'hint' => $request->input('hint'),
                'BookingData' => now()->locale($request->input('locale', $request->lang ?? 'en'))->translatedFormat('d F Y'),
            ];

            try {


                if ($request->lang === 'ar') {

                    Mail::to($user->email)
                        ->send(new BookingSuccessMail($data, $payment->stripe_payment_link));
                } else {
                    Mail::to($user->email)
                        ->send(new BookingSuccessMail($data, $payment->stripe_payment_link));
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
