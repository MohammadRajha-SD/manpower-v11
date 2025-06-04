<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrialEndingSoonAdmin extends Notification
{
    use Queueable;

    public $subscription;

    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Provider Trial Ending Soon')
            ->greeting('Hello Admin,')
            ->line('A provider trial is ending in 1-3 days.')
            ->line('Provider: ' . $this->subscription->provider?->name)
            ->line('Plan: ' . $this->subscription?->name)
            ->line('Trial Ends At: ' . $this->subscription?->trial_ends_at)
            ->action('View Dashboard', url('/admin/providers'))
            ->line('Thanks!');
    }
}
