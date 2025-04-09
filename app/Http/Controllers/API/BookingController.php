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
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

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

        $subtotal = $price * $validated['quantity'];
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
        $booking->booking_at = now();

        $payment = Payment::create([
            'amount' => $totalAmount,
            'description' => 'Transaction for Booking #' . $booking->id,
            'user_id' => $request->user_id,
            'payment_status_id' => $payment_status->id,
            'payment_method_id' => $payment_method->id,
        ]);

        $booking->payment_id = $payment->id;
        $booking->save();

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

        $user = User::find($request->user_id);

        $cc_email = env('CC_EMAIL') ?? 'info@hpower.ae';

        Mail::to($user->email)
            ->cc($cc_email)
            ->send(new SendPaymentLink($user, $checkoutSession->url));

        return response()->json([
            'status' => 'success',
            'message' => 'Booking created and payment email sent.',
            'booking' => $booking,
            'payment_url' => $checkoutSession->url
        ]);
    }


    public function success(Request $request)
    {
        $booking = Booking::findOrFail($request->booking_id);

        $payment = $booking->payment->update([
            'payment_status_id' => 2,
        ]);

        return redirect(env('FRONTEND_URL'));
    }

    public function cancel(Request $request)
    {
        return redirect(env('FRONTEND_URL'));
    }
}
