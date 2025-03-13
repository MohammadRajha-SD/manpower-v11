<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProviderSubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $link;
    public $name;
    public $email;
    public $price;

    public function __construct($link, $name, $email, $price)
    {
        $this->link = $link;
        $this->name = $name;
        $this->email = $email;
        $this->price = $price;
    }

    public function build()
    {
        return $this->subject('Your Payment Link')
                    ->view('emails.provider-subscription')
                    ->with(['link' => $this->link, 'price' => $this->price, 'name' => $this->name, 'email'=> $this->email]);
    }
}
