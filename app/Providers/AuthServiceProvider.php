<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $resetUrl = env('FRONTEND_URL', 'http://localhost:3000') . "/reset-password/{$token}?email=" . urlencode($notifiable->email);

            return (new MailMessage)
                ->subject('طلب إعادة تعيين كلمة المرور')
                ->greeting('مرحبًا ' . $notifiable->name . ',')
                ->line('لقد استلمنا طلبًا لإعادة تعيين كلمة المرور الخاصة بك.')
                ->action('إعادة تعيين كلمة المرور', $resetUrl)
                ->line('إذا لم تكن قد طلبت إعادة تعيين كلمة المرور، يرجى تجاهل هذه الرسالة.')
                ->salutation('مع أطيب التحيات، فريق شركتك');

            // ->line('') 
            // ->line('© ' . date('Y') . 'Hpower جميع الحقوق محفوظة.') 
        });
    }
}
