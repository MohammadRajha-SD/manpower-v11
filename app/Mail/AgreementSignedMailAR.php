<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgreementSignedMailAR extends Mailable
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
        return $this->markdown('emails.provider.agreement_signed_ar')
            ->subject("مرحباً بك، {$this->name} – أنت الآن جزء رسمي من H Power")
            ->with([
                'name' => $this->name,
                'agreementLink' => $this->attachmentPath,
            ]);
    }
}
