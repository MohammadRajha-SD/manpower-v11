<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordCustom extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = env('FRONTEND_URL', 'https://hpower.ae') . '/reset-password/' . $this->token . '?email=' . $notifiable->getEmailForPasswordReset();
        
        return (new MailMessage)
            ->from('noreply@hpower.ae', 'HPOWER')
            ->subject(__('lang.reset_password_subject'))
            ->line(__('lang.reset_password_line1'))
            ->action(__('lang.reset_password_action'), $url)
            ->line(__('lang.reset_password_line2'));
    }
}
