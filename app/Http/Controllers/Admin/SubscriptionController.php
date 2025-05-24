<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SubscriptionDataTable;
use App\Http\Controllers\Controller;
use App\Mail\ProviderSubscriptionMail;
use App\Models\Pack;
use App\Models\Provider;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Stripe\Subscription as StripeSubscription;
use Stripe\Customer;
use Illuminate\Support\Facades\Validator;
use Stripe\Refund;
use Stripe\Invoice;

class SubscriptionController extends Controller
{
    public function index(SubscriptionDataTable $dataTable)
    {
        return $dataTable->render('admins.subscriptions.index');
    }

    public function create()
    {
        $packs = Pack::all();
        $providers = Provider::all();

        return view('admins.subscriptions.create', compact('packs', 'providers'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'provider_id' => 'required|exists:providers,id',
            'pack_id' => 'required|exists:packs,id',
        ]);

        DB::beginTransaction();

        try {
            $pack = Pack::findOrFail($request->pack_id);
            $provider = Provider::findOrFail($request->provider_id);

            Stripe::setApiKey(env('STRIPE_SECRET'));

            // Create or Retrieve Stripe Customer
            if (!$provider->stripe_id) {
                $customer = Customer::create([
                    'email' => $provider->email,
                    'name' => $provider->name,
                ]);
                $provider->stripe_id = $customer->id;
                $provider->save();
            } else {
                $customer = Customer::retrieve($provider->stripe_id);
            }

            // Create a Stripe subscription for the provider
            $subscriptionStripe = StripeSubscription::create([
                'customer' => $customer->id,
                'items' => [['price' => $pack->stripe_plan_id]],
                'payment_behavior' => 'default_incomplete',
                'expand' => ['latest_invoice.payment_intent'],
            ]);

            $subscription = new Subscription();
            $subscription->pack_id = $pack->id;
            $subscription->provider_id = $provider->id;
            $subscription->quantity = 1;
            $subscription->price = $pack->price;
            $subscription->name = $pack->text;
            $subscription->status = 'incomplete';
            $subscription->stripe_id = $subscriptionStripe->id;
            $subscription->stripe_status = $subscriptionStripe->status;
            $subscription->stripe_plan = $pack->stripe_plan_id;
            $subscription->ends_at = $pack->number_of_months > 0 ? Carbon::now()->addMonths($pack->number_of_months) : Carbon::now();
            $subscription->trial_ends_at = $pack->trial_days > 0
                ? Carbon::now()->addDays($pack->trial_days)
                : Carbon::now();

            $subscription->save();

            DB::commit();

            return redirect()->route('admin.subscriptions.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.subscriptions')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function edit($id)
    {
        $subscription = Subscription::findOrFail($id);
        $packs = Pack::all();
        $providers = Provider::all();

        return view('admins.subscriptions.edit', compact('subscription', 'packs', 'providers'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $this->validate($request, [
            'provider_id' => 'nullable|exists:providers,id',
            'pack_id' => 'required|exists:packs,id',
        ]);

        DB::beginTransaction();

        try {
            $pack = Pack::findOrFail($request->pack_id);
            $provider = Provider::findOrFail($request->provider_id);

            Stripe::setApiKey(env('STRIPE_SECRET'));

            // Update Stripe subscription if the pack changes
            if ($subscription->pack_id !== $pack->id) {
                $stripeSubscription = StripeSubscription::retrieve($subscription->stripe_id);
                $stripeSubscription->items = [['price' => $pack->stripe_plan_id]];
                $stripeSubscription->save();

                $subscription->stripe_plan = $pack->stripe_plan_id;
                $subscription->price = $pack->price;
                $subscription->ends_at = $pack->number_of_months > 0 ? Carbon::now()->addMonths($pack->number_of_months) : now();
            }

            $subscription->pack_id = $pack->id;
            $subscription->provider_id = $provider->id;
            $subscription->name = $pack->text;
            $subscription->status = $request->status ?? 'active';
            $subscription->save();

            DB::commit();

            return redirect()->route('admin.subscriptions.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.subscriptions')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')->with('success', __('lang.deleted_successfully', ['operator' => __('lang.subscriptions')]));
    }

    public function disable($id)
    {
        $subscription = Subscription::findOrFail($id);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $stripeSubscription = StripeSubscription::retrieve($subscription->stripe_id);
            $stripeSubscription->cancel();

            $subscription->stripe_status = 'cancelled';
            $subscription->status = 'disabled';
            $subscription->save();

            return redirect()->route('admin.subscriptions.index')->with('success', __('lang.subscription_disabled'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function extendSubscriptionByMonths(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $validator = Validator::make($request->all(), [
            'subscription_id' => 'required|string|exists:subscriptions,id',
            'number_month' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid data',
                'messages' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $subscriptionLocal = Subscription::findOrFail($request->subscription_id);

            // Prevent extending a canceled subscription
            if ($subscriptionLocal->status === 'disabled' || $subscriptionLocal->stripe_status === 'canceled') {
                return redirect()->back()->with('error', __('lang.subscription_canceled_cannot_extend'));
            }

            // ✅ Make sure we have a valid Stripe subscription ID
            if (!$subscriptionLocal->stripe_id) {
                return redirect()->back()->with('error', __('lang.stripe_subscription_not_found'));
            }

            // ✅ Retrieve subscription from Stripe using the correct Stripe Subscription ID
            $stripeSubscription = StripeSubscription::retrieve($subscriptionLocal->stripe_id);


            // Check if the subscription is active before extending
            if (!in_array($stripeSubscription->status, ['active', 'trialing'])) {
                return redirect()->back()->with('error', __('lang.subscription_not_active'));
            }

            $newPeriodEnd = Carbon::createFromTimestamp($stripeSubscription->current_period_end)
                ->addMonths($request->number_month)
                ->timestamp;

            // ✅ Update the subscription period in Stripe
            StripeSubscription::update(
                $stripeSubscription->id,
                [
                    'trial_end' => $newPeriodEnd,
                    'proration_behavior' => 'none',
                ]
            );

            // ✅ Update local database subscription
            $subscriptionLocal->ends_at = Carbon::createFromTimestamp($newPeriodEnd);
            $subscriptionLocal->trial_ends_at = Carbon::createFromTimestamp($newPeriodEnd);
            $subscriptionLocal->save();

            DB::commit();

            return redirect()->route('admin.subscriptions.index')->with('success', __('lang.subscription_updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.subscriptions.index')->with('error', $e->getMessage());
        }
    }

    public function paymentSuccess(Request $request, $subscriptionId)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $subscription = Subscription::with('plan')->findOrFail($subscriptionId);

        // Retrieve Checkout Session from Stripe
        $session = Session::retrieve($subscription->stripe_id);

        if (!isset($session->subscription)) {
            return redirect()->route('admin.subscriptions.index')->with('error', __('Subscription ID not found in Stripe.'));
        }

        // ✅ Get the subscription from Stripe
        $stripeSubscription = StripeSubscription::retrieve($session->subscription);

        if ($stripeSubscription->status === 'active') {
            $number_of_months = $subscription->plan->number_of_months;
            $endsAt = Carbon::now()->addMonths($number_of_months);

            $subscription->update([
                'stripe_id' => $stripeSubscription->id,
                'stripe_status' => 'paid',
                'status' => 'active',
                'amount_refunded' => 0,
                'ends_at' => $endsAt,
            ]);

            return redirect()->route('admin.subscriptions.index')->with('success', __('lang.subscription_activated_successfully'));
        }

        return redirect()->route('admin.subscriptions.index')->with('error', __('lang.payment_not_completed'));
    }

    public function paymentCancel($subscriptionId)
    {
        return redirect()->route('admin.subscriptions.index')->with('error', 'Payment was cancelled.');
    }

    public function generatePaymentLink($subscriptionId)
    {
        $subscription = Subscription::with('plan', 'provider')->findOrFail($subscriptionId);

        // Check if the subscription status is pending or ended
        if (in_array($subscription->stripe_status, ['active', 'paid'])) {
            return response()->json(['error' => 'Subscription is not eligible for payment'], 400);
        }

        // Set Stripe API Key
        Stripe::setApiKey(config('services.stripe.secret'));

        $number_of_months = $subscription->plan->number_of_months;

        // Determine interval based on months
        if ($number_of_months === 1) {
            $interval = 'month';
        } elseif ($number_of_months === 12) {
            $interval = 'year';
        } else {
            $interval = 'month';
        }

        // Create Stripe Checkout Session
        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'aed',
                        'product_data' => [
                            'name' => $subscription->name,
                        ],
                        'unit_amount' => $subscription->price * 100,
                        'recurring' => ['interval' => $interval]
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'subscription',
            'success_url' => route('admin.subscriptions.success', ['subscription' => $subscriptionId]),
            'cancel_url' => route('admin.subscriptions.cancel', ['subscription' => $subscriptionId]),
        ]);

        // Update subscription with payment link
        $subscription->update([
            'stripe_id' => $checkoutSession->id,
            'stripe_status' => 'pending',
        ]);

        return response()->json([
            'url' => $checkoutSession->url,
            'email' => $subscription->provider->email,
            'name' => $subscription->provider->name,
            'price' => $subscription->price
        ]);
    }

    public function sendPaymentEmail(Request $request)
    {
        $email = $request->email;
        $name = $request->name;
        $price = $request->price;
        $link = $request->link;

        $cc_email = env('CC_EMAIL') ?? 'info@hpower.ae';
        Mail::to($email)->cc($cc_email)->send(new ProviderSubscriptionMail($link, $name, $email, $price));

        return response()->json(['success' => 'Email sent successfully']);
    }

    public function refund(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'amount' => 'required|numeric|min:0.1',
        ]);

        $subscription = Subscription::findOrFail($request->subscription_id);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // ✅ Get Stripe Subscription directly (without using Session)
            $stripeSubscription = StripeSubscription::retrieve($subscription->stripe_id);

            // ✅ Get the latest invoice
            if (!$stripeSubscription->latest_invoice) {
                return redirect()->back()->with('error', __('lang.no_invoice_found_for_subscription'));
            }

            $invoice = Invoice::retrieve($stripeSubscription->latest_invoice);

            // ✅ Get Charge ID from invoice
            $chargeId = $invoice->charge ?? null;

            if (!$chargeId) {
                return redirect()->back()->with('error', __('lang.no_charge_found_for_subscription'));
            }

            // ✅ Check if full refund or partial refund
            $price = $subscription->price;
            $refunded = $subscription->amount_refunded;
            $amount = $request->amount;

            $remaining = $price - $refunded;


            // Calculate refund type (full or partial)
            if ($amount >= $remaining) {
                // Update subscription status to refunded (full refund)
                if ($amount == $remaining) {
                    $subscription->update([
                        'stripe_status' => 'refunded',
                        'status' => 'refunded',
                        'ends_at' => now(),
                        'amount_refunded' => $price,
                    ]);

                    $stripeSubscription->cancel();

                    $refund = Refund::create([
                        'charge' => $chargeId,
                        'amount' => $remaining * 100,
                    ]);

                    return redirect()->back()->with('success', __('lang.full_refund_processed_and_subscription_canceled'));
                } else {
                    return redirect()->back()->with('error', __('lang.refund_amount_exceeds_remaining_balance'));
                }
            } else {
                // Partial refund
                if ($amount > $remaining) {
                    return redirect()->back()->with('error', __('lang.refund_amount_exceeds_remaining_balance'));
                }

                // Create partial refund on Stripe
                $refund = Refund::create([
                    'charge' => $chargeId,
                    'amount' => $amount * 100,
                ]);

                // Update the amount refunded in the subscription record
                $subscription->update([
                    'amount_refunded' => $refunded + $amount,
                ]);

                return redirect()->back()->with('success', __('lang.partial_refund_processed'));
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
