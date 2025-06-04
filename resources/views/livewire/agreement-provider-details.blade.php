<div>
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        {{ __('lang.agreement') }} {{ __('lang.details') }} <strong class="ml-2"> #AG_11{{
                            $agreement->id
                            }}</strong>
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
                        @else
                        <button class="btn btn-sm btn-success" wire:click.prevent="editAgreement({{ $agreement->id }})">
                            <i class="fas fa-edit"></i>
                        </button>
                        @endif

                        <button type="button" class="close" wire:click="closeModal">
                            <span>&times;</span>
                        </button>
                    </div>
                </div>

                <div class="modal-body" style="max-height: 60vh; overflow-y: auto; font-size: 0.85rem;">
                    @if($agreement)
                    <table class="table table-bordered table-striped table-sm">
                        <tbody>
                            <tr>
                                <th>{{ __('lang.provider_request') }}</th>
                                <td>
                                    @if($editMode)
                                    <select class="form-control form-control-sm"
                                        wire:model.live="form.provider_request_id">
                                        <option value="">Select</option>
                                        @foreach ($prequests as $prequest)
                                        <option value="{{ $prequest?->id }}">
                                            <strong>#PRR_11{{ $prequest?->id }}</strong> - {{
                                            $prequest?->company_name }} - {{
                                            $prequest?->contact_email }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @else
                                    @if ($agreement->prequest)
                                    <strong>#PRR_11{{ $agreement->prequest?->id }}</strong> - {{
                                    $agreement->prequest?->company_name }} - {{
                                    $agreement->prequest?->contact_email }}
                                    @else
                                    N/A
                                    @endif
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.e_provider') }}</th>
                                <td>
                                    @if($editMode)
                                    <select class="form-control form-control-sm" wire:model.live="form.provider_id">
                                        <option value="">Select</option>
                                        @foreach ($providers as $provider)
                                        <option value="{{ $provider?->id }}">
                                            <strong>#PR_11{{ $provider?->id }}</strong> - {{
                                            $provider?->name }} - {{
                                            $provider?->email }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @else
                                    @if ($agreement->provider)
                                    <strong>#PR_11{{ $agreement->provider?->id }}</strong> - {{
                                    $agreement->provider?->name }} - {{
                                    $agreement->provider?->email }}
                                    @else
                                    N/A
                                    @endif
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>{{ __('lang.plan') }}</th>
                                <td>
                                    @if($editMode)
                                    <select class="form-control form-control-sm" wire:model.defer="form.plan_id">
                                        @foreach ($plans as $plan)
                                        <option value="{{ $plan?->id }}" {{ $plan->id == $agreement->plan?->id ?
                                            'selected': ''}}>
                                            {!! $plan?->text !!}
                                        </option>
                                        @endforeach
                                    </select>
                                    @else
                                    {{ $agreement->plan?->text }}
                                    @endif
                                </td>

                            </tr>

                            <tr>
                                <th>{{ __('lang.signed') }}</th>
                                <td>
                                    @if($editMode)
                                    <select class="form-control form-control-sm" wire:model.defer="form.signed">
                                        <option value="1">{{ __('lang.yes') }}</option>
                                        <option value="0">{{ __('lang.no') }}</option>
                                    </select>
                                    @else
                                    {{ $agreement->signed ? __('lang.yes') : __('lang.no') }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>{{ __('lang.company_name') }}</th>
                                <td>
                                    @if($editMode)
                                    <input type="text" class="form-control form-control-sm"
                                        wire:model.defer="form.name">
                                    @else
                                    {{ $agreement->name }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>{{ __('lang.license_number') }}</th>
                                <td>
                                    @if($editMode)
                                    <input type="text" class="form-control form-control-sm"
                                        wire:model.defer="form.license_number">
                                    @else
                                    {{ $agreement->license_number }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>{{ __('lang.email') }}</th>
                                <td>
                                    @if($editMode)
                                    <input type="email" class="form-control form-control-sm"
                                        wire:model.defer="form.email">
                                    @else
                                    {{ $agreement->email }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>{{ __('lang.phone') }}</th>
                                <td>
                                    @if($editMode)
                                    <input type="text" class="form-control form-control-sm"
                                        wire:model.defer="form.phone">
                                    @else
                                    {{ $agreement->phone }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>{{ __('lang.commission') }}</th>
                                <td>
                                    @if($editMode)
                                    <input type="number" class="form-control form-control-sm"
                                        wire:model.defer="form.commission">
                                    @else
                                    {{ $agreement->commission }}%
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>{{ __('lang.terms') }}</th>
                                <td>
                                    @if($editMode)
                                    <select class="form-control form-control-sm" wire:model.defer="form.terms">
                                        <option value="1">{{ __('lang.yes') }}</option>
                                        <option value="0">{{ __('lang.no') }}</option>
                                    </select>
                                    @else
                                    {{ $agreement->terms ? __('lang.yes') : __('lang.no') }}
                                    @endif
                                </td>
                            </tr>


                            {{-- <tr>
                                <th>{{ __('lang.address') }}</th>
                                <td>{{ $agreement->address ?? 'N/A' }}</td>
                            </tr> --}}

                            <tr>
                                <th>{{ __('lang.created_at') }}</th>
                                <td>{{ $agreement->updated_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                    @if($agreement->signature)
                    <div class="mt-4 text-center">
                        <h6>{{ __('lang.signature') }}</h6>
                        <img src="{{ asset($agreement->signature) }}" alt="Signature"
                            style="max-width: 100%; max-height: 150px; object-fit: contain; border: 1px solid #ddd; padding: 8px;" />
                    </div>
                    @endif

                    @else
                    <p class="text-center">{{ __('lang.no_agreement_details') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>