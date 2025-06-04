<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pack;
use App\Models\Agreement;
use App\Models\Provider;
use App\Models\ProviderRequest;

class AgreementProviderCreate extends Component
{
    public $showModal = false;
    public $editMode = false;
    public $plans;
    public $prequests;
    public $providers;
    public $form = [];
    public $agreement;

    protected $listeners = ['open-agreement-provider-create-modal' => 'openModal'];


    public function mount()
    {
        $this->plans = Pack::all();
        $this->prequests = ProviderRequest::all();
        $this->providers = Provider::all();
        $this->form = [
            'name' => '',
            'license_number' => '',
            'email' => '',
            'phone' => '',
            'commission' => 0,
            'terms' => 0,
            'signed' => 0,
            'plan_id' => $this->plans->first()?->id,
            'provider_request_id' => null,
            'provider_id' => null,
        ];
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

        Agreement::create($this->form);

        $this->closeModal();

        session()->flash('success', __('lang.agreement.created'));

        return redirect()->route('admin.agreements.index');
    }
    
    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'form']);
    }


    public function render()
    {
        return view('livewire.agreement-provider-create');
    }
}
