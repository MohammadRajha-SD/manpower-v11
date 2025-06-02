<div>
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('lang.booking') }} {{ __('lang.details') }} </h5>
                    <button type="button" class="close" wire:click="closeModal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="max-height: 60vh; overflow-y: auto; font-size: 0.85rem;">
                    @if($booking)
                    <table class="table table-bordered table-striped table-sm">
                        <tbody>
                            <tr>
                                <th>{{ __('lang.user_name') }}</th>
                                <td>{{ $booking?->user?->name }} </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.email') }}</th>
                                <td>{{ $booking?->user?->email }} </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.phone') }}</th>
                                <td>{{ $booking?->user?->phone_number }} </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.e_provider') }} {{ __('lang.e_provider_name') }}</th>
                                <td>{{ $booking?->service?->provider?->name }} </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.e_provider') }} {{ __('lang.email') }}</th>
                                <td>{{ $booking?->service?->provider?->email }} </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.e_provider') }} {{ __('lang.phone') }}</th>
                                <td>{{ $booking?->service?->provider?->phone_number }} </td>
                            </tr>
                              <tr>
                                <th>{{ __('lang.e_provider') }} {{ __('lang.description') }}</th>
                                <td>{!! $booking?->service?->provider?->description ?? 'N/A' !!} </td>
                            </tr>
                            <tr>
                                <th>{{ __('lang.created_at') }}</th>
                                <td>{{ $booking->updated_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                    @else
                    <p class="text-center">{{ __('lang.no_booking_details') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>