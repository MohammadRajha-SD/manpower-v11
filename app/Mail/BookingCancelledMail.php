<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

   
    public $booking;
    public $provider;
    public $service;
    public $reason;
    
    public function __construct($booking, $reason)
    {
        $this->booking = $booking;
        $this->provider = $booking->service->provider;
        $this->service = $booking->service;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Booking Cancelled Notification')
            ->view('emails.booking_cancelled');
    }
}
