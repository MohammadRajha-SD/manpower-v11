<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPaymentLink extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $paymentUrl;

    public function __construct($user, $paymentUrl)
    {
        $this->user = $user;
        $this->paymentUrl = $paymentUrl;
    }

    public function build()
    {
        return $this->subject('Complete Your Booking Payment')
                    ->view('emails.payment_link');
    }
}
