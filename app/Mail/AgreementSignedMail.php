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

    public $attachmentPath;
    public $name;

    public function __construct($name, $attachmentPath)
    {
        $this->name = $name;
        $this->attachmentPath = $attachmentPath;
    }

    public function build()
    {
        return $this->markdown('emails.provider.agreement_signed')
            ->subject("Welcome, {$this->name} – You’re Officially Part of H Power")
            ->with([
                'name' => $this->name,
                'agreementLink' => $this->attachmentPath,
            ]);
    }
}
