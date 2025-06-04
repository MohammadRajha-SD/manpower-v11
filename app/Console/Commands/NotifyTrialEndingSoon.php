<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Notifications\TrialEndingSoon;
use App\Notifications\TrialEndingSoonAdmin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifyTrialEndingSoon extends Command
{
    protected $signature = 'notify:trial-ending';
    protected $description = 'Notify providers and admin 1-3 days before trial ends';

    public function handle()
    {
        $now = Carbon::now()->startOfDay();
        $in3Days = Carbon::now()->addDays(3)->endOfDay();

        $subscriptions = Subscription::with('provider')
            ->whereBetween('trial_ends_at', [$now, $in3Days])
            ->get();
        $subscriptions = Subscription::with('provider')
            ->whereBetween('trial_ends_at', [$now, $in3Days])
            ->get();

        Log::info($now);
        Log::info($in3Days);
        Log::info($subscriptions);

        foreach ($subscriptions as $subscription) {
            // 1. Send notification to the provider
            if ($subscription->provider) {
                $subscription->provider->notify(new TrialEndingSoon($subscription));
            }

            // 2. Send email to admin ONLY 
            Notification::route('mail', 'info@hpower.ae')
                ->notify(new TrialEndingSoonAdmin($subscription));
        }

        $this->info('Notifications sent for trials ending in 1-3 days. at ' . $now);
    }
}
