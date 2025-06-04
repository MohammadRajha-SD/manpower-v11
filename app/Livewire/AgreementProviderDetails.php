<?php

namespace App\Livewire;

use App\Models\Agreement;
use App\Models\Pack;
use Livewire\Component;
use App\Models\Provider;
use App\Models\ProviderRequest;

class AgreementProviderDetails extends Component
{
    public $agreement;
    public $showModal = false;
    public $editMode = false;
    protected $listeners = ['open-agreement-provider-details-modal' => 'openModal'];
    public $form = [];
    public $plans;
    public $prequests;
    public $providers;
    public function openModal($id)
    {
        $this->agreement = Agreement::where('id', $id)->first();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'agreement']);
    }

    public function editAgreement($id)
    {
        $this->agreement = Agreement::findOrFail($id);
        $this->plans = Pack::all();
        $this->prequests = ProviderRequest::all();
        $this->providers = Provider::all();
        $this->form = [
            'name' => $this->agreement->name,
            'license_number' => $this->agreement->license_number,
            'email' => $this->agreement->email,
            'phone' => $this->agreement->phone,
            'commission' => $this->agreement->commission,
            'terms' => $this->agreement->terms,
            'signed' => $this->agreement->signed,
            'plan_id' => $this->agreement?->plan?->id,
            'provider_request_id' => $this->agreement?->prequest?->id ?? null,
            'provider_id' => $this->agreement?->provider?->id ?? null,
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
            'form.provider_request_id' => 'nullable|exists:provider_requests,id',
            'form.provider_id' => 'nullable|exists:providers,id',
        ]);

        $this->agreement->update($this->form);
        $this->editAgreement($this->agreement->id);
        $this->editMode = false;
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
    }


    public function render()
    {
        return view('livewire.agreement-provider-details');
    }
}
