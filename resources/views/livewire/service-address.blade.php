<div>
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        {{ __('lang.booking_e_service') }} {{ __('lang.e_provider_addresses') }} {{ __('lang.details')
                        }}
                    </h5>

                    <div>
                        @if ($editMode)
                        <button class="btn btn-sm btn-secondary" wire:click.prevent="toggleEditMode"
                            title="{{ __('lang.close') }}">
                            <i class="fas fa-times"></i>
                        </button>
                        <button class="btn btn-sm btn-primary" wire:click.prevent="save" title="{{ __('lang.save') }}">
                            <i class="fas fa-save"></i>
                        </button>
                        @endif
                        <button type="button" class="close" wire:click="closeModal">
                            <span>&times;</span>
                        </button>
                    </div>
                </div>

                <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                    @if($service)
                    <div class="d-flex justify-content-between">
                        <h6 class="mt-3">{{ __('lang.booking_e_service') }} {{ __('lang.e_provider_name') }}: {{
                            $service->name }}</h6>

                        @if(!$editMode && !$creating)
                        <button class="btn btn-sm btn-success mb-3" wire:click="startCreating">
                            <i class="fas fa-plus"></i>
                        </button>
                        @endif
                    </div>



                    @if($service->addresses->isNotEmpty())
                    <table class="table table-bordered table-striped table-sm mt-2">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('lang.address') }}</th>
                                <th>{{ __('lang.service_charge') }}</th>
                                <th>{{ __('lang.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($service->addresses as $index => $address)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($editMode && $addressId === $address->id)
                                    <select name="address" class="form-control select2" wire:model.defer="form.address">
                                        @foreach (config('emirates') as $emirate => $cities)
                                        <optgroup label="{{ $emirate }}">
                                            @foreach ($cities as $city)
                                            <option value="{{ $city['slug'] }}">{{ $city['name'] }}</option>
                                            @endforeach
                                        </optgroup>
                                        @endforeach
                                    </select>

                                    @error('form.address') <span class="text-danger">{{ $message }}</span> @enderror
                                    @else
                                    {{ $address->address }}
                                    @endif
                                </td>
                                <td>
                                    @if($editMode && $addressId === $address->id)
                                    <input type="number" step="0.01" class="form-control form-control-sm"
                                        wire:model.defer="form.service_charge">
                                    @error('form.service_charge') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @else
                                    {{ setting('default_currency_code') }} {{ number_format($address->service_charge, 2)
                                    }}
                                    @endif
                                </td>
                                <td>
                                    @if(!$editMode)
                                    <button class="btn btn-sm btn-primary" wire:click="editAddress({{ $address->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                        wire:click="confirmDeleteAddress({{ $address->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @elseif($addressId === $address->id)
                                    <span class="text-muted">{{ __('lang.editing') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-muted mt-2">{{ __('lang.no_service_addresses') }}</p>
                    @endif



                    @if($creating)
                    <hr>
                    <h6>{{ __('lang.add_new_address') }}</h6>
                    <form wire:submit.prevent="createAddress">
                        <div class="form-group">
                            <label>{{ __('lang.address') }}</label>
                            <select name="address" class="form-control select2" wire:model.defer="createForm.address">
                                <option value="">{{ __('lang.select_address') }}</option>
                                @foreach (config('emirates') as $emirate => $cities)
                                <optgroup label="{{ $emirate }}">
                                    @foreach ($cities as $city)
                                    <option value="{{ $city['slug'] }}">{{ $city['name'] }}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                            @error('createForm.address') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mt-2">
                            <label>{{ __('lang.service_charge') }}</label>
                            <input type="number" step="0.01" class="form-control form-control-sm"
                                wire:model.defer="createForm.service_charge" min="0">
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('lang.save') }}</button>
                            <button type="button" class="btn btn-secondary btn-sm"
                                wire:click="$set('creating', false)">{{ __('lang.cancel') }}</button>
                        </div>
                    </form>
                    @endif

                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>