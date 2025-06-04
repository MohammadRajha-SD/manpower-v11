<?php

namespace App\Livewire;

use Livewire\Component;

class AgreementProviderCreateAction extends Component
{

    public function openAgreementModal()
    {
        $this->dispatch('open-agreement-provider-create-modal');
    }

    public function render()
    {
        return view('livewire.agreement-provider-create-action');
    }
}
