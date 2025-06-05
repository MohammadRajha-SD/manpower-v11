<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProviderWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $attachmentPath;

    public function __construct($name, $attachmentPath)
    {
        $this->name = $name;
        $this->attachmentPath = $attachmentPath;
    }
    public function build()
    {
        return $this->markdown('emails.provider.welcome')
            ->subject("Welcome aboard, {$this->name}")
            ->with([
                'name' => $this->name,
                'agreementLink' => $this->attachmentPath,
            ]);
    }
}
