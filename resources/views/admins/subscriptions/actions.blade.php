<td class="text-center">
    <div class="btn-group">
        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-cogs"></i> {{ __('lang.actions') }}
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            @if (!in_array($subscription->stripe_status , ['paid', 'active']))
            <form action="{{ route('admin.subscriptions.generate-payment-link', $subscription->id) }}" method="POST"
                style="display: inline;">
                <a href="{{ route("admin.subscriptions.generate-payment-link", $subscription->id) }}"
                    class="dropdown-item text-warning generate-payment-link"
                    data-toggle="tooltip" title="Create payment link">
                    <i class="fas fa-credit-card"></i>{{ __('lang.create_payment_link') }}
                </a>
            </form>
            @endif

            <a class="dropdown-item text-warning" href="#" data-toggle="modal"
                data-target="#extendSubscriptionModal{{ $subscription->id }}" data-toggle="tooltip"
                title="{{ __('lang.extend_subscription') }}">
                <i class="fas fa-calendar-plus"></i> {{ __('lang.extend_subscription') }}
            </a>

            <a class="dropdown-item text-danger" href="{{ route('admin.subscriptions.disable', $subscription->id) }}"
                onclick="return confirm('{{ __('lang.disable_confirmation') }}');" data-toggle="tooltip"
                title="{{ __('lang.disable_subscription') }}">
                <i class="fas fa-ban"></i> {{ __('lang.disable_subscription') }}
            </a>

            <a class="dropdown-item text-primary" href="{{ route('admin.subscriptions.edit', $subscription->id) }}"
                data-toggle="tooltip" title="{{ __('lang.edit_subscription') }}">
                <i class="fas fa-edit"></i> {{ __('lang.edit_subscription') }}
            </a>

            <!-- Handle the delete form submission with a button -->
            
            @if($subscription->stripe_status == 'refunded') 
            <a href='{{ route('admin.subscriptions.destroy', $subscription->id) }}' class='btn text-danger btn-sm ml-2
                delete-item'><i class='fa fa-trash'></i> {{ __('lang.delete_subscription') }}</a> 
            @endif
            @if($subscription->stripe_status == 'paid')
            <button class="btn text-danger  btn-sm ml-2 refund-button" data-id="{{ $subscription->id }}" data-amount="{{ $subscription->remainingAmount() }}">
                <i class="fa fa-undo"></i> {{ __('lang.refund') }}
            </button>
            @endif
        </div>
    </div>

    <!-- Modal for Extending Subscription -->
    <div class="modal fade" id="extendSubscriptionModal{{ $subscription->id }}" tabindex="-1"
        aria-labelledby="extendSubscriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="extendSubscriptionModalLabel">{{ trans('lang.extend_subscription') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.subscriptions.extends') }}" method="POST"
                        id="extendSubscriptionForm{{ $subscription->id }}">
                        @csrf
                        <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                        <div class="form-group">
                            <label for="number_month">{{ trans('lang.number_of_months') }}</label>
                            <input type="number" class="form-control" name="number_month" id="number_month" required
                                min="1" placeholder="{{ trans('lang.enter_months') }}">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('lang.close')
                        }}</button>
                    <button type="submit" class="btn btn-primary" form="extendSubscriptionForm{{ $subscription->id }}">
                        {{ trans('lang.extend') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Refunding Subscription -->
    <div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="refundForm" method="POST" action="{{ route('admin.subscriptions.refund-payment') }}">
                @csrf
                <input type="hidden" name="subscription_id" id="refundSubscriptionId">
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('lang.refund_subscription') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <div class="modal-body">
                        <label>{{ __('lang.refund_amount') }}</label>
                        <input type="number" name="amount" id="refundAmount" class="form-control" step="0.1" min="0">
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('lang.refund') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.cancel') }}</button>
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