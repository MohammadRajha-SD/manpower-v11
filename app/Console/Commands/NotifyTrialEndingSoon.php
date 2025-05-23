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
    protected $description = 'Notify providers and admin 3 days before trial ends';

    public function handle()
    {
        $targetDate = Carbon::now()->addDays(2)->startOfDay();

        $subscriptions = Subscription::with('provider')->whereDate('trial_ends_at', $targetDate)->get();

        foreach ($subscriptions as $subscription) {
            // 1. Send notification to the provider
            if ($subscription->provider) {
                $subscription->provider->notify(new TrialEndingSoon($subscription));
            }

            // 2. Send email to admin ONLY 
            Notification::route('mail', 'mohammadrajha2@gmail.com')
                ->notify(new TrialEndingSoonAdmin($subscription));
        }
        $this->info('Notifications sent for trials ending in 3 days.');
    }
}
