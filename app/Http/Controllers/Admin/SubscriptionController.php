<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SubscriptionDataTable;
use App\Http\Controllers\Controller;
use App\Mail\ProviderSubscriptionMail;
use App\Models\Pack;
use App\Models\Provider;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Stripe\Subscription as StripeSubscription;
use Stripe\Customer;
use Illuminate\Support\Facades\Validator;

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
        $users = User::all();

        return view('admins.subscriptions.create', compact('packs', 'users', 'providers'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'provider_id' => 'nullable|exists:providers,id',
            'pack_id' => 'required|exists:packs,id',
            // 'status' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $pack = Pack::findOrFail($request->pack_id);
            $user = User::findOrFail($request->user_id);

            Stripe::setApiKey(env('STRIPE_SECRET'));

            // Create or Retrieve Stripe Customer
            if (!$user->stripe_id) {
                $customer = Customer::create([
                    'email' => $user->email,
                    'name' => $user->name,
                ]);
                $user->stripe_id = $customer->id;
                $user->save();
            } else {
                $customer = Customer::retrieve($user->stripe_id);
            }

            // Create a Stripe subscription for the user
            $subscriptionStripe = StripeSubscription::create([
                'customer' => $customer->id,
                'items' => [['price' => $pack->stripe_plan_id]],
                'payment_behavior' => 'default_incomplete',
                'expand' => ['latest_invoice.payment_intent'],
            ]);

            $subscription = new Subscription();
            $subscription->pack_id = $pack->id;
            $subscription->user_id = $user->id;
            $subscription->provider_id = $request->provider_id;
            $subscription->quantity = 1;
            $subscription->price = $pack->price;
            $subscription->name = $pack->text;
            $subscription->status = 'incomplete';
            $subscription->stripe_id = $subscriptionStripe->id;
            $subscription->stripe_status = $subscriptionStripe->status;
            $subscription->stripe_plan = $pack->stripe_plan_id;
            $subscription->ends_at = $pack->number_of_months > 0 ? Carbon::now()->addMonths($pack->number_of_months) : now();
            $subscription->trial_ends_at = $pack->number_of_months > 0 ? Carbon::now()->addMonths($pack->number_of_months) : now();
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
        $users = User::all();

        return view('admins.subscriptions.edit', compact('subscription', 'packs', 'providers', 'users'));
    }


    public function update(Request $request, Subscription $subscription)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'provider_id' => 'nullable|exists:providers,id',
            'pack_id' => 'required|exists:packs,id',
            'status' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $pack = Pack::findOrFail($request->pack_id);
            $user = User::findOrFail($request->user_id);

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
            $subscription->user_id = $user->id;
            $subscription->provider_id = $request->provider_id;
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
            return redirect()->back()->with('error', __('lang.something_went_wrong') . ' ' . $e->getMessage());
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
        $subscription = Subscription::with('plan', 'provider', 'user')->findOrFail($subscriptionId);

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
                        'currency' => 'usd',
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
            'email' => $subscription->user->email,
            'name' => $subscription->user->name,
            'price' => $subscription->price
        ]);
    }

    public function sendPaymentEmail(Request $request)
    {
        $email = $request->email;
        $name = $request->name;
        $price = $request->price;
        $link = $request->link;

        // TODO:
        Mail::to($email)->cc('info@manpowerforu.com')->send(new ProviderSubscriptionMail($link, $name, $email, $price));

        return response()->json(['success' => 'Email sent successfully']);
    }
}
