<?php

namespace App\Livewire;

use App\Models\Pack;
use App\Models\ProviderRequest;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AgreementForm extends Component
{
    public $showModal = false;
    public $requestId;
    public $request;
    public $plans;
    public $provider_requests;

    public $name;
    public $email;
    public $phone;
    public $license_number;
    public $provider_request_id;
    public $plan_id;
    public $commission;
    public $terms = 0;
    public $signed = 0;

    protected $listeners = ['open-agreement-modal' => 'openModal'];

    public function mount()
    {
        $this->plans = Pack::all();
        $this->provider_requests = ProviderRequest::all();
    }

    public function openModal($id)
    {
        $this->showModal = true;

        $prequest = ProviderRequest::where('id', $id)->first() ?? $id;
        $this->provider_request_id = $prequest->id;
        $this->plan_id = $this->plans->first()?->id;

        $this->phone = $prequest->phone_number;
        $this->name = $prequest->company_name;
        $this->email = $prequest->contact_email;
    }

    public function closeModal()
    {
        $this->reset(['showModal']);
    }

    public function submit()
    {
        $this->validate([
            'provider_request_id' => 'required|exists:provider_requests,id',
            'plan_id' => 'required|exists:packs,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'license_number' => 'nullable|string|max:255',
            'commission' => 'nullable|numeric|min:0',
            'terms' => 'boolean',
            'signed' => 'boolean',
        ]);

        $prequest = ProviderRequest::where('id', $this->provider_request_id)->first();

        $data = [
            'provider_request_id' => $this->provider_request_id,
            'plan_id' => $this->plan_id,
            'signed' => $this->signed,
            'name' => $this->name,
            'license_number' => $this->license_number,
            'email' => $this->email,
            'phone' => $this->phone,
            'commission' => $this->commission ?? 0,
            'terms' => $this->terms,
            'uid' => $prequest->uid,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('agreements')->insert($data);

        $this->reset([
            'provider_request_id',
            'plan_id',
            'signed',
            'name',
            'license_number',
            'email',
            'phone',
            'commission',
            'terms',
        ]);

        $this->showModal = false;

        return redirect()->route('admin.provider-requests.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.agreement')]));
    }

    public function render()
    {
        return view('livewire.agreement-form');
    }
}