@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.booking_plural')" :desc="__('lang.booking_desc')"
        :table_name="__('lang.booking_table')" :route="route('admin.bookings.index')" />

    <x-admins.cards.content :name1="__('lang.booking_table')" :name2="__('lang.booking_create')"
        route1="admin.bookings.index" route2="admin.bookings.create" :isEditMode="true" :name3="__('lang.booking_edit')"
        :route3="['admin.bookings.edit', $booking->id]">
        <form action="{{route('admin.bookings.update', $booking->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Booking Status Id Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="booking_status_id" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.booking_booking_status_id") }}
                        </label>
                        <div class="col-md-9">
                            <select name="booking_status_id" class="select2 form-control">
                                @foreach ($booking_statuses as $booking_status)
                                <option value="{{ $booking_status->id }}" {{ old('booking_status_id', $booking->
                                    booking_status_id)==$booking_status->
                                    id ? 'selected' : '' }}>{{ ucwords($booking_status->status) }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.booking_booking_status_id_help") }}</div>
                        </div>
                    </div>

                    <!-- Address Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="address" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.booking_address") }}
                        </label>
                        <div class="col-md-9">
                            <select name="address" class="form-control select2">
                                @foreach (config('emirates') as $emirate => $cities)
                                <optgroup label="{{ $emirate }}">
                                    @foreach ($cities as $city)
                                    <option value="{{ $city['slug'] }}" {{$booking->address == $city['slug'] ?
                                        'selected':''}}>{{ $city['name'] }}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>

                            <div class="form-text text-muted">
                                {{ trans("lang.booking_address_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status Id Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="payment_status_id" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.booking_payment_id") }}
                        </label>
                        <div class="col-md-9">
                            <select name="payment_status_id" class="select2 form-control">
                                @foreach ($payment_statuses as $payment_status)
                                <option value="{{ $payment_status->id }}" {{ old('payment_status_id', $booking->
                                    payment_status_id)==$payment_status->
                                    id ? 'selected' : '' }}>{{
                                    $payment_status->status }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.booking_payment_id_help") }}</div>
                        </div>
                    </div>

                    <!-- Hint Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="hint" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.booking_hint") }}
                        </label>
                        <div class="col-md-9">
                            <textarea name="hint" class="form-control"
                                placeholder="{{ trans('lang.booking_hint_placeholder') }}">{!! old('hint', $booking->hint) !!}</textarea>
                            <div class="form-text text-muted">{{ trans("lang.booking_hint_help") }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Payment Status Id Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="payment_status_id" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.booking_payment_id") }}
                        </label>
                        <div class="col-md-9">
                            <select name="payment_status_id" class="select2 form-control">
                                @foreach ($payment_statuses as $payment_status)
                                <option value="{{ $payment_status->id }}" {{ old('payment_status_id', $booking->
                                    payment_status_id)==$payment_status->
                                    id ? 'selected' : '' }}>{{
                                    $payment_status->status }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.booking_payment_id_help") }}</div>
                        </div>
                    </div>
                    
                    <!-- Booking At Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="booking_at" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.booking_booking_at") }}
                        </label>
                        <div class="col-md-9">
                            <div class="input-group datepicker booking_at" data-target-input="nearest">
                                <input type="text" name="booking_at" id="booking_at"
                                    class="form-control datetimepicker-input"
                                    placeholder="{{ trans('lang.booking_booking_at_placeholder') }}"
                                    data-target=".datepicker.booking_at" data-toggle="datetimepicker"
                                    value="{{ $booking->booking_at }}" autocomplete="off">
                                <div id="widgetParentId"></div>
                                <div class="input-group-append" data-target=".datepicker.booking_at"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fas fa-business-time"></i></div>
                                </div>
                            </div>
                            <div class="form-text text-muted">
                                {{ trans("lang.booking_booking_at_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Start At Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="start_at" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.booking_start_at") }}
                        </label>
                        <div class="col-md-9">
                            <div class="input-group datepicker start_at" data-target-input="nearest">
                                <input type="text" name="start_at" id="start_at"
                                    class="form-control datetimepicker-input"
                                    placeholder="{{ trans('lang.booking_start_at_placeholder') }}"
                                    data-target=".datepicker.start_at" data-toggle="datetimepicker" autocomplete="off"
                                    value="{{ $booking->start_at }}">
                                <div id="widgetParentId"></div>
                                <div class="input-group-append" data-target=".datepicker.start_at"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fas fa-business-time"></i></div>
                                </div>
                            </div>
                            <div class="form-text text-muted">
                                {{ trans("lang.booking_start_at_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Ends At Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="ends_at" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.booking_ends_at") }}
                        </label>
                        <div class="col-md-9">
                            <div class="input-group datepicker ends_at" data-target-input="nearest">
                                <input type="text" name="ends_at" id="ends_at" class="form-control datetimepicker-input"
                                    placeholder="{{ trans('lang.booking_ends_at_placeholder') }}"
                                    data-target=".datepicker.ends_at" data-toggle="datetimepicker" autocomplete="off"
                                    value="{{ $booking->ends_at }}">

                                <div id="widgetParentId"></div>
                                <div class="input-group-append" data-target=".datepicker.ends_at"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fas fa-business-time"></i></div>
                                </div>
                            </div>
                            <div class="form-text text-muted">
                                {{ trans("lang.booking_ends_at_help") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <label for="cancel" class="control-label my-0 mx-3">
                        {{ trans("lang.booking_cancel") }}
                    </label>
                    <input type="hidden" name="cancel" value="0" id="hidden_cancel">
                    <span class="icheck-{{ setting('theme_color') }}">
                        <input type="checkbox" name="cancel" value="1" id="cancel" {{ $booking->cancel == 1 ? 'checked'
                        :
                        '' }}>
                        <label for="cancel"></label>
                    </span>
                </div>

                <button type="submit" class="btn bg-{{ setting('theme_color') }} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fas fa-save"></i> {{ trans('lang.save') }} {{ trans('lang.booking') }}
                </button>

                <a href="{{ route('admin.bookings.index') }}" class="btn btn-default">
                    <i class="fas fa-undo"></i> {{ trans('lang.cancel') }}
                </a>
            </div>
        </form>
    </x-admins.cards.content>

    @push('scripts_lib')
    <script src="{{asset('vendor/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('vendor/summernote/summernote.min.js')}}"></script>
    <script src="{{asset('vendor/dropzone/min/dropzone.min.js')}}"></script>
    <script src="{{asset('vendor/moment/moment.min.js')}}"></script>
    <script src="{{asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    @endpush
</x-admin-layout>