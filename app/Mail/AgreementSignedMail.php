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
    public $signed_at;

    public function __construct($name, $attachmentPath, $signed_at)
    {
        $this->name = $name;
        $this->attachmentPath = $attachmentPath;
        $this->signed_at = $signed_at;
    }

    public function build()
    {
        return $this->markdown('emails.provider.agreement_signed')
            ->subject("Welcome, {$this->name} – You’re Officially Part of H Power")
            ->with([
                'name' => $this->name,
                'signed_at' => $this->signed_at,
                'agreementLink' => $this->attachmentPath,
            ]);
    }
}
