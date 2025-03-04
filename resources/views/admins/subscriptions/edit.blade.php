@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.subscriptions')" :desc="__('lang.subscription_list')"
        :route="route('admin.subscriptions.index')" />

    <x-admins.cards.content :name1="__('lang.subscription_list')" route1="admin.subscriptions.index"
        :isCreateMode="false" :isEditMode="true" :name3="__('lang.edit_subscription')"
        :route3="['admin.subscriptions.edit', $subscription->id]">
        <form action="{{route('admin.subscriptions.update', $subscription->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                {{-- ENDS AT --}}
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label class=' control-label text-md-left mx-1 my-2'>
                            {{ trans("lang.subscription_ends_at") }}
                        </label>

                        <div class="input-group datepicker ends_at" data-target-input="nearest" style="width:100%;">
                            <input type="text" name="ends_at" class="form-control datetimepicker-input"
                                value="{{ \Carbon\Carbon::parse($subscription->ends_at)->format('Y-m-d H:i:s') }}"
                                placeholder="{{ __(" lang.subscription_ends_at") }}" data-target=".datepicker.ends_at"
                                data-toggle="datetimepicker" autocomplete="off">
                            <div id="widgetParentId"></div>
                            <div class="input-group-append" data-target=".datepicker.ends_at"
                                data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fas fa-business-time"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ENDS AT // --}}

                {{-- STRIPE STATUS --}}
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label class=' control-label text-md-left mx-1 my-2'>
                            {!! trans("lang.subscription_status") !!}
                        </label>

                        <select class=" select2 form-control" name="stripe_status">
                            <option value="active" {{$subscription->stripe_status =='active' ? 'selected' :
                                ''
                                }}>{{ __('lang.active') }}</option>
                            <option value="incomplete" {{$subscription->stripe_status =='incomplete' ?
                                'selected'
                                : '' }}>{{
                                __('lang.incomplete') }}</option>
                            <option value="disabled" {{$subscription->stripe_status =='disabled' ?
                                'selected' :
                                ''
                                }}>{{
                                __('lang.disabled') }}</option>
                        </select>
                    </div>
                </div>
                {{-- STRIPE STATUS // --}}

                {{-- pack_id --}}
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label class='control-label text-md-left mx-1 my-2'>
                            {!! trans('lang.plan') !!}
                        </label>

                        <select class="select2 form-control" name="pack_id" id="pack_id">
                            <option value="" selected disabled>@lang('lang.select')</option>
                            @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ $subscription->pack_id == $plan->id ? 'selected' : ''
                                }}>
                                {{ $plan->text }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- pack_id // --}}

            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.subscriptions')}}
                </button>

                <a href="{!! route('admin.subscriptions.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
                    {{trans('lang.cancel')}}</a>
            </div>
        </form>
    </x-admins.cards.content>

    @push('scripts_lib')
    <script src="{{asset('vendor/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('vendor/summernote/summernote.min.js')}}"></script>
    <script src="{{asset('vendor/moment/moment.min.js')}}"></script>
    <script src="{{asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    @endpush
</x-admin-layout>