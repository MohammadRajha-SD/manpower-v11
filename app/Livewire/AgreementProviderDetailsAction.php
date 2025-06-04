<?php

namespace App\Livewire;

use Livewire\Component;

class AgreementProviderDetailsAction extends Component
{
    
    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function openAgreementModal()
    {
        $this->dispatch('open-agreement-provider-details-modal', id: $this->id);
    }

    public function render()
    {
        return view('livewire.agreement-provider-details-action');
    }
}
