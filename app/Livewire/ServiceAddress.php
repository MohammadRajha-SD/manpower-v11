<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Service;
use App\Models\ServiceAddress as ServiceAddressModel;

class ServiceAddress extends Component
{
    public $showModal = false;
    public $editMode = false;
    public $service;

    public $addressId;
    public $form = [
        'address' => '',
        'service_charge' => '',
    ];

    protected $listeners = [
        'open-service-address-details-modal' => 'openModal',
        'deleteConfirmed' => 'deleteAddress',
    ];

    public function openModal($id)
    {
        $this->service = Service::with('addresses')->findOrFail($id);
        $this->reset(['editMode', 'form', 'addressId']);
        $this->showModal = true;
    }

    public function editAddress($id)
    {
        $address = ServiceAddressModel::findOrFail($id);
        $this->addressId = $address->id;
        $this->form['address'] = $address->address;
        $this->form['service_charge'] = $address->service_charge;
        $this->editMode = true;
    }

    public function save()
    {
        $this->validate([
            'form.address' => 'required|string',
            'form.service_charge' => 'required|numeric|min:0',
        ]);

        $address = ServiceAddressModel::findOrFail($this->addressId);
        $address->update($this->form);

        $this->service = Service::with('addresses')->find($this->service->id); // Refresh
        $this->reset(['editMode', 'form', 'addressId']);
    }

    public function toggleEditMode()
    {
        $this->editMode = false;
        $this->reset(['form', 'addressId']);
    }

    public function confirmDeleteAddress($id)
    {
        $this->dispatch('confirm-delete', ['id' => $id]);
    }


    public function deleteAddress($id)
    {
        ServiceAddressModel::find($id)?->delete();
        $this->service = Service::with('addresses')->find($this->service->id);
    }

    public function closeModal()
    {
        $this->reset(['showModal', 'service', 'editMode', 'form', 'addressId']);
    }



    public $creating = false;   // flag for create form
    public $createForm = [
        'address' => '',
        'service_charge' => 0,
    ];

    public function startCreating()
    {
        $this->creating = true;
        $this->editMode = false;
        $this->reset('createForm');
    }

    public function createAddress()
    {
        $this->validate([
            'createForm.address' => 'required|string',
            'createForm.service_charge' => 'required|numeric|min:0',
        ]);

        $this->service->addresses()->create([
            'address' => $this->createForm['address'],
            'service_charge' => $this->createForm['service_charge'],
        ]);

        // Refresh service and reset create form
        $this->service = Service::with('addresses')->find($this->service->id);
        $this->creating = false;
        $this->reset('createForm');
    }

    public function render()
    {
        return view('livewire.service-address');
    }
}
