<div>
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        {{ __('lang.agreement.create') }}
                    </h5>

                    <div>
                        <button class="btn btn-sm btn-primary" wire:click.prevent="save" title="{{ __('lang.save') }}">
                            <i class="fas fa-save"></i>
                        </button>
                        <button type="button" class="close" wire:click="closeModal">
                            <span>&times;</span>
                        </button>
                    </div>
                </div>

                <div class="modal-body" style="max-height: 60vh; overflow-y: auto; font-size: 0.85rem;">
                    <table class="table table-bordered table-striped table-sm">
                        <tbody>
                            <tr>
                                <th>{{ __('lang.provider_request') }}</th>
                                <td>
                                    <select class="form-control form-control-sm"
                                        wire:model.defer="form.provider_request_id">
                                        <option value="">Select</option>
                                        @foreach ($prequests as $prequest)
                                        <option value="{{ $prequest?->id }}">
                                            <strong>#PRR_11{{ $prequest?->id }}</strong> - {{
                                            $prequest?->company_name }} - {{
                                            $prequest?->contact_email }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.e_provider') }}</th>
                                <td>
                                    <select class="form-control form-control-sm" wire:model.defer="form.provider_id">
                                        <option value="">Select</option>
                                        @foreach ($providers as $provider)
                                        <option value="{{ $provider?->id }}">
                                            <strong>#PR_11{{ $provider?->id }}</strong> - {{
                                            $provider?->name }} - {{
                                            $provider?->email }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.plan') }}</th>
                                <td>
                                    <select class="form-control form-control-sm" wire:model.defer="form.plan_id">
                                        @foreach ($plans as $plan)
                                        <option value="{{ $plan?->id }}">
                                            {!! $plan?->text !!}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.signed') }}</th>
                                <td>
                                    <select class="form-control form-control-sm" wire:model.defer="form.signed">
                                        <option value="1">{{ __('lang.yes') }}</option>
                                        <option value="0">{{ __('lang.no') }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.company_name') }}</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm"
                                        wire:model.defer="form.name">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.license_number') }}</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm"
                                        wire:model.defer="form.license_number">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.email') }}</th>
                                <td>
                                    <input type="email" class="form-control form-control-sm"
                                        wire:model.defer="form.email">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.phone') }}</th>
                                <td>
                                    <input type="text" class="form-control form-control-sm"
                                        wire:model.defer="form.phone">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.commission') }}</th>
                                <td>
                                    <input type="number" class="form-control form-control-sm"
                                        wire:model.defer="form.commission" min="0" max="100">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.terms') }}</th>
                                <td>
                                    <select class="form-control form-control-sm" wire:model.defer="form.terms">
                                        <option value="1">{{ __('lang.yes') }}</option>
                                        <option value="0">{{ __('lang.no') }}</option>
                                    </select>
                                </td>
                            </tr>


                            {{-- <tr>
                                <th>{{ __('lang.address') }}</th>
                                <td>{{ $agreement->address ?? 'N/A' }}</td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>