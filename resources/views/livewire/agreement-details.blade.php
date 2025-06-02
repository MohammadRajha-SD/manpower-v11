<div>
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('lang.agreement') }} {{ __('lang.details') }} </h5>
                    <button type="button" class="close" wire:click="closeModal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="max-height: 60vh; overflow-y: auto; font-size: 0.85rem;">
                    @if($agreement)
                    <table class="table table-bordered table-striped table-sm">
                        <tbody>
                            <tr>
                                <th>{{ __('lang.provider_request') }}</th>
                                <td>{{ $agreement->prequest?->id }} - {{ $agreement->prequest?->company_name }} - {{
                                    $agreement->prequest?->contact_email }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.plan') }}</th>
                                <td>{{ $agreement->plan?->text }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.signed') }}</th>
                                <td>{{ $agreement->signed ? __('lang.yes') : __('lang.no') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.company_name') }}</th>
                                <td>{{ $agreement->name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.license_number') }}</th>
                                <td>{{ $agreement->license_number }}</td>
                            </tr>
                            {{-- <tr>
                                <th>{{ __('lang.address') }}</th>
                                <td>{{ $agreement->address ?? 'N/A' }}</td>
                            </tr> --}}
                            <tr>
                                <th>{{ __('lang.email') }}</th>
                                <td>{{ $agreement->email }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.phone') }}</th>
                                <td>{{ $agreement->phone }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.commission') }}</th>
                                <td>{{ $agreement->commission }}%</td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.terms') }}</th>
                                <td>{{ $agreement->terms ? __('lang.yes') : __('lang.no') }}</td>
                            </tr>
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