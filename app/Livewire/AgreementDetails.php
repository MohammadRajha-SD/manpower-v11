<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Agreement;
use App\Models\Pack;

class AgreementDetails extends Component
{
    public $showModal = false;
    public $agreement;
    public $plans;
    public $editMode = false;
    public $form = [];
    protected $listeners = ['open-agreement-details-modal' => 'openModal'];

    public function editAgreement($id)
    {
        $this->agreement = Agreement::findOrFail($id);
        $this->plans = Pack::all();
        $this->form = [
            'name' => $this->agreement->name,
            'license_number' => $this->agreement->license_number,
            'email' => $this->agreement->email,
            'phone' => $this->agreement->phone,
            'commission' => $this->agreement->commission,
            'terms' => $this->agreement->terms,
            'signed' => $this->agreement->signed,
            'plan_id' => $this->agreement?->plan?->id,
        ];
        $this->editMode = true;
    }

    public function save()
    {
        $this->validate([
            'form.name' => 'required',
            'form.license_number' => 'required',
            'form.email' => 'required|email',
            'form.phone' => 'required',
            'form.commission' => 'required|numeric',
            'form.terms' => 'boolean',
            'form.signed' => 'boolean',
            'form.plan_id' => 'required|integer|exists:packs,id',
        ]);

        $this->agreement->update($this->form);
        $this->editMode = false;
    }

    public function openModal($id)
    {
        $this->agreement = Agreement::where('provider_request_id', $id)->first();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'agreement']);
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
    }

    public function render()
    {
        return view('livewire.agreement-details');
    }
}
