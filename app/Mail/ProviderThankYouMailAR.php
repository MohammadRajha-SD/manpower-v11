<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProviderThankYouMailAR extends Mailable
{
    use Queueable, SerializesModels;

   public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function build()
    {
        return $this->from('noreply@hpower.ae', 'اتش باور')
                    ->subject('شكرًا لتقديم طلبك')
                    ->view('emails.thank-you-ar');
    }
}
