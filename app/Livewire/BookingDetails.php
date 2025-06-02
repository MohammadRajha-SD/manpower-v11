<?php

namespace App\Livewire;

use App\Models\Booking;
use Livewire\Component;

class BookingDetails extends Component
{
    public $showModal = false;
    public $booking;

    protected $listeners = ['open-booking-details-modal' => 'openModal'];

    public function openModal($id)
    {
        $this->booking = Booking::with("user", "service.provider")->where('id', $id)->first();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'booking']);
    }

    public function render()
    {
        return view('livewire.booking-details');
    }
}
