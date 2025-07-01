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
    public $signed_at;
  
    public function __construct($name, $attachmentPath, $signed_at)
    {
        $this->name = $name;
        $this->attachmentPath = $attachmentPath;        
        $this->signed_at = $signed_at;
    }

    public function build()
    {
        return $this->markdown('emails.provider.agreement_signed_ar')
            ->subject("مرحباً بك، {$this->name} – أنت الآن جزء رسمي من H Power")
            ->with([
                'name' => $this->name,
                 'signed_at' => $this->signed_at,
                'agreementLink' => $this->attachmentPath,
            ]);
    }
}
