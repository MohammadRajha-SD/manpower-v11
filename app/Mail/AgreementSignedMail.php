<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgreementSignedMail extends Mailable
{
    use Queueable, SerializesModels;
 use Queueable, SerializesModels;

    public $id;
    public $name;

    public function __construct($name, $id)
    {
        $this->name = $name;
        $this->id = $id;
    }

    public function build()
    {
        return $this->subject('Thank You for Signing')
                    ->view('emails.provider.agreement_signed');
    }
}
