<td class="text-center">
    <div class="btn-group">
        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-cogs"></i> {{ __('lang.actions') }}
        </button>

        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item text-success" href="{{ route('admin.bookings.edit', $booking->id) }}">
                <i class="fas fa-edit"></i> {{ __('lang.edit') }}
            </a>

            @if($booking->payment?->payment_status?->status == 'Refunded')
            <a href="{{ route('admin.bookings.destroy', $booking->id) }}"
                class="btn text-danger btn-sm ml-2 delete-item">
                <i class="fa fa-trash"></i> {{ __('lang.delete') }}
            </a>
            @endif

            @if($booking->payment->payment_status->status == 'Paid' && $booking->booking_status?->status != 'Cancelled')
            <button class="btn text-warning  btn-sm ml-2 refund-button" data-id="{{ $booking->id }}"
                data-amount="{{ $booking->payment->amount }}">
                <i class="fa fa-undo"></i> {{ __('lang.refund') }}
            </button>
            @endif


            <livewire:booking-details-action :id="$booking->id"/>
        </div>
    </div>

    <!-- Modal for Refunding Subscription -->
    <div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="refundForm" method="POST" action="{{ route('admin.bookings.cancel') }}">
                @csrf
                <input type="hidden" name="booking_id" id="refundSubscriptionId">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('lang.refund') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="refundAmount">{{ __('lang.refund_amount') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">AED</span>
                            </div>
                            <input type="number" name="amount" readonly id="refundAmount" class="form-control"
                                step="0.1" min="0">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('lang.refund') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.cancel')
                            }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.refund-button').on('click', function () {
                let subscriptionId = $(this).data('id');
                let amount = $(this).data('amount');

                $('#refundSubscriptionId').val(subscriptionId);
                $('#refundAmount').attr('max', amount).val(amount); 
                $('#refundModal').modal('show');

                return;
            });
        });
    </script>
</td>