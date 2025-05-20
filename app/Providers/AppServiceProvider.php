<?php

namespace App\Providers;

use App\Models\Provider;
use App\Observers\ProviderObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Provider::observe(ProviderObserver::class);
        
        // Config::set('mail.default', setting('mail_driver', env('MAIL_MAILER', 'smtp')));

        // Config::set('mail.mailers.smtp.transport', 'smtp');
        // Config::set('mail.mailers.smtp.host', setting('mail_host', env('MAIL_HOST')));
        // Config::set('mail.mailers.smtp.port', setting('mail_port', env('MAIL_PORT')));
        // Config::set('mail.mailers.smtp.username', setting('mail_username', env('MAIL_USERNAME')));
        // Config::set('mail.mailers.smtp.password', setting('mail_password', env('MAIL_PASSWORD')));
        // Config::set('mail.mailers.smtp.encryption', setting('mail_encryption', env('MAIL_ENCRYPTION')));
    
        // Config::set('mail.from.address', setting('mail_from_address', env('MAIL_FROM_ADDRESS')));
        // Config::set('mail.from.name', setting('mail_from_name', env('MAIL_FROM_NAME', config('app.name'))));
    }
}
