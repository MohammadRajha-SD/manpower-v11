<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Agreement;

class AgreementDetails extends Component
{
    public $showModal = false;
    public $agreement;

    protected $listeners = ['open-agreement-details-modal' => 'openModal'];

    public function openModal($id)
    {
        $this->agreement = Agreement::where('provider_request_id', $id)->first();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'agreement']);
    }

    public function render()
    {
        return view('livewire.agreement-details');
    }
}
