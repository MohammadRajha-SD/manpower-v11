<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPaymentLinkAR extends Mailable
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
        return $this->from('noreply@hpower.ae', 'HPOWER')
            ->subject('أكمل عملية الدفع الخاصة بحجزك')
            ->view('emails.payment_link_ar');
    }
}
