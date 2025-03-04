<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SubscriptionDataTable;
use App\Http\Controllers\Controller;
use App\Models\Pack;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Subscription as StripeSubscription;

class SubscriptionController extends Controller
{
    public function index(SubscriptionDataTable $dataTable)
    {
        return $dataTable->render('admins.subscriptions.index');
    }
    public function create()
    {
        $plans = Pack::all();
        return view('admins.subscriptions.create', compact( 'plans'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'pack_id' => 'required',
            'stripe_status' => 'required',
            'ends_at' => 'required|date|after:today',
        ]);

        // TODO:
        $subscription = new Subscription();
        $subscription->ends_at = $request->ends_at;
        $subscription->status = $request->stripe_status;
        $subscription->pack_id = $request->pack_id;
        $subscription->save();

        return redirect()->route('admin.subscriptions.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.subscriptions')]));
    }
    public function edit($id)
    {
        $subscription = Subscription::findOrFail($id);
        $plans = Pack::all();

        return view('admins.subscriptions.edit', compact('subscription', 'plans'));
    }

    public function update(string $id, Request $request){
        $this->validate($request,[
            'pack_id' => 'required',
            'stripe_status' => 'required',
            'ends_at' => 'required|date|after:today',
        ]);

        $subscription = Subscription::findOrFail($id);
        $subscription->ends_at = $request->ends_at;
        $subscription->status = $request->stripe_status;
        $subscription->pack_id = $request->pack_id;
        $subscription->save();

        return redirect()->route('admin.subscriptions.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.subscriptions')]));
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')->with('success', __('lang.deleted_successfully', ['operator' => __('lang.subscriptions')]));
    }

    // TODO:
    // public function disable($id)
    // {
    //     // $subscription = Subscription::where('user_id', $id)->first();
    //     $subscription = Subscription::findOrFail($id);


    //     Stripe::setApiKey(env('STRIPE_SECRET'));

    //     try {
    //         // Retrieve the subscription from Stripe
    //         $stripeSubscription = StripeSubscription::retrieve($subscription->stripe_id);

    //         // Update the subscription's status to 'canceled' or 'inactive' on Stripe
    //         $stripeSubscription->cancel();  // This will cancel the subscription

    //         // Alternatively, you can update the subscription's status in Stripe if needed
    //         // $stripeSubscription->status = 'inactive';
    //         // $stripeSubscription->save();

    //         // Now, update the local database to reflect the change
    //         $subscription->stripe_status = 'disabled'; // Update your local database status
    //         $subscription->status = 'disabled'; // Update your local database status
    //         $subscription->save();

    //         Flash::success(__('lang.subscription_disabled'));

    //         return redirect()->route('provider_subscriptions.index');
    //     } catch (\Exception $e) {
    //         // Handle errors from Stripe API
    //         flash('Error disabling subscription: ' . $e->getMessage())->error();
    //         return redirect()->route('provider_subscriptions.index');
    //     }


    //     // return redirect()->route('provider_subscriptions.index');
    // }

    // public function extendSubscriptionByMonths(Request $request)
    // {
    //     try {
    //         // Set the secret key for Stripe
    //         Stripe::setApiKey(env('STRIPE_SECRET'));

    //         // Validate incoming request data
    //         $validator = Validator::make($request->all(), [
    //             'subscription_id' => 'required|string', // Subscription ID is required
    //             'number_month' => 'required|integer|min:1', // Ensure valid number of months
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'error' => 'Invalid data',
    //                 'messages' => $validator->errors(),
    //             ], 422);
    //         }


    //         $subscriptionId = $request->input('subscription_id');

    //         $subscriptionLocal = Subscription::findOrFail($subscriptionId);


    //         $numberOfMonths = $request->input('number_month'); // Get the number of months to add

    //         // Retrieve the current subscription from Stripe
    //         $subscription = StripeSubscription::retrieve($subscriptionLocal->stripe_id);


    //         // Get the current period_end timestamp (end of the current billing cycle)
    //         $currentPeriodEnd = $subscription->current_period_end;

    //         // Calculate the new period end by adding the specified number of months
    //         $newPeriodEnd = Carbon::createFromTimestamp($currentPeriodEnd)->addMonths($numberOfMonths)->timestamp;


    //         try {

    //             $updatedSubscription = StripeSubscription::update(
    //                 $subscription->id,
    //                 [
    //                     'trial_end' => $newPeriodEnd,
    //                     'proration_behavior' => 'none', // Don't apply proration, it's a gift
    //                 ]
    //             );

    //             $subscriptionLocal->ends_at = Carbon::createFromTimestamp($newPeriodEnd); // Update the local 'ends_at' field
    //             $subscriptionLocal->save();


    //         } catch (\Exception $e) {
    //             // Catch and debug any issues during the Stripe update process
    //             dd('Stripe API Error', $e->getMessage(), $e->getTrace());
    //         }

    //         Flash::success(__('lang.subscription_updated'));
    //         return redirect()->route('provider_subscriptions.index');

    //     } catch (\Exception $e) {
    //         dd('Exception Thrown', $e->getMessage(), $e->getTrace());
    //         // Handle errors from Stripe API
    //         Flash::error(__('lang.error_updating_subscription', ['error' => $e->getMessage()]));
    //         return redirect()->route('provider_subscriptions.index');
    //     }
    // }
}
