<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Pack;
use App\Models\Provider;
use App\Models\Subscription;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription as StripeSubscription;
use Illuminate\Support\Facades\App;

class SubscriptionController extends Controller
{
    public function NewSubscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pack_id' => 'required|exists:packs,id',
            'provider_id' => 'required|exists:providers,id',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            // $lang = $request->lang ?? 'en';
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
            // $subscription->name = $pack->getTranslation('text', $lang);
            $subscription->status = 'incomplete';
            $subscription->stripe_id = $subscriptionStripe->id;
            $subscription->stripe_status = $subscriptionStripe->status;
            $subscription->stripe_plan = $pack->stripe_plan_id;
            $subscription->ends_at = $pack->number_of_months > 0 ? Carbon::now()->addMonths($pack->number_of_months) : now();
            $subscription->trial_ends_at = $pack->number_of_months > 0 ? Carbon::now()->addMonths($pack->number_of_months) : now();
            $subscription->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => __('lang.saved_successfully', ['operator' => __('lang.subscriptions')]),
                'data' => $subscription,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while saving the review.',
                'error' => $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }
}
