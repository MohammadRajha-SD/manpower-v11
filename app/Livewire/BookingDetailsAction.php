<?php

namespace App\Livewire;

use Livewire\Component;

class BookingDetailsAction extends Component
{

      public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function openBookingModal()
    {
        $this->dispatch('open-booking-details-modal', id: $this->id);
    }
    public function render()
    {
        return view('livewire.booking-details-action');
    }
}
