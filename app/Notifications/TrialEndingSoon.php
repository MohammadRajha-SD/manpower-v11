<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrialEndingSoon extends Notification
{
    use Queueable;

    public $subscription;

    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Trial Ending Soon')
            ->greeting("Hello " . ($notifiable->name ?? ''))
            ->line('Your free trial will end in 1-3 days.')
            ->line('Plan: ' . $this->subscription?->name)
            ->line('Trial Ends At: ' . $this->subscription?->trial_ends_at)
            // ->action('Manage Subscription', url('/dashboard')) 
            ->line('Thank you for using our service!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'provider_id' => $this->subscription?->provider?->id,
            'title' => 'Trial Ending Soon',
            'body' => 'Provider "' . $this->subscription->provider?->name . '" has a trial ending in 3 days (Plan: ' . $this->subscription?->name . 'Trial Ends At: ' . $this->subscription?->trial_ends_at . ')',
        ];
    }
}
