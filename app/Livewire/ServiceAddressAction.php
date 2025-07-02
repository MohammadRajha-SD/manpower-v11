<?php

namespace App\Livewire;

use Livewire\Component;

class ServiceAddressAction extends Component
{

    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function openServiceAddressModal()
    {
        $this->dispatch('open-service-address-details-modal', id: $this->id);
    }

    public function render()
    {
        return view('livewire.service-address-action');
    }
}
