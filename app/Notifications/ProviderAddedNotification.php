<?php

namespace App\Notifications;

use App\Models\Provider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProviderAddedNotification extends Notification
{
    use Queueable;

    public $provider;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    public function via($notifiable)
    {
        $types = ['database'];

        if (setting('enable_email_notifications', false)) {
            array_push($types, 'mail');
        }

        return $types;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMailx(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->markdown("notifications::provider", ['provider' => $this->provider])
            ->subject(trans('lang.new_provider_registeration') . " | " . setting('app_name', ''))
            ->greeting(trans('lang.new_provider_registeration'))
            ->action(trans('lang.booking_details'), env('FRONTEND_URL') . '/login');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'provider_id' => $this->provider['id'],
            'title' => __('lang.new_provider_registeration'),
            'body' => __('lang.new_provider_registeration'),
            'icon' => image_item($this->provider?->image),
        ];
    }
}
