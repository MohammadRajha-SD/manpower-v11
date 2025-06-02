<?php

namespace App\Livewire;

use Livewire\Component;

class AgreementDetailsAction extends Component
{
    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function openAgreementModal()
    {
        $this->dispatch('open-agreement-details-modal', id: $this->id);
    }
    
    public function render()
    {
        return view('livewire.agreement-details-action');
    }
}
