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
    public $provider;
    public $attachmentPath;

    public function __construct($provider, $attachmentPath)
    {
        $this->provider = $provider;
        $this->attachmentPath = $attachmentPath;
    }


    public function build()
    {
        return $this->markdown('emails.provider.welcome')
            ->subject("Welcome, {$this->provider->contact_person} – You’re Officially Part of H Power!")
            ->with([
                'provider' => $this->provider,
                'downloadUrl' => $this->attachmentPath,
            ]);
    }
}
