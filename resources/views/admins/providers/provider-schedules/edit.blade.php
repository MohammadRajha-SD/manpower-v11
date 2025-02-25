@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.availability_hour_plural')" :desc="__('lang.availability_hour_desc')"
        :table_name="__('lang.availability_hour_table')" :route="route('admin.provider-schedules.index')" />

    <x-admins.cards.content :name1="__('lang.availability_hour_table')" :name2="__('lang.availability_hour_create')"
        route1="admin.provider-schedules.index" route2="admin.provider-schedules.create" :isEditMode="true" :name3="__('lang.availability_hour_edit')" :route3="['admin.provider-schedules.edit', $schedule->id]">

        <form action="{{route('admin.provider-schedules.update', $schedule->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Day Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="day" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.availability_hour_day") }}
                        </label>
                        <div class="col-md-9">
                            <select id="day" name="day" class="select2 form-control">
                                @foreach(getDays() as $day)
                                <option value="{{ $day }}" {{ $day==$schedule->day ? 'selected' : '' }}>{{ ucwords($day)
                                    }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.availability_hour_day_help") }}</div>
                        </div>
                    </div>

                    <!-- Start At Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="start_at" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.availability_hour_start_at") }}
                        </label>
                        <div class="col-md-9">
                            <div class="input-group timepicker start_at" data-target-input="nearest">
                                <input type="text" id="start_at" name="start_at" value="{{ $schedule->start_at }}"
                                    class="form-control datetimepicker-input"
                                    placeholder="{{ trans('lang.availability_hour_start_at_placeholder') }}"
                                    data-target=".timepicker.start_at" data-toggle="datetimepicker" autocomplete="off">
                                <div class="input-group-append" data-target=".timepicker.start_at"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fas fa-business-time"></i></div>
                                </div>
                            </div>
                            <div class="form-text text-muted">{{ trans("lang.availability_hour_start_at_help") }}</div>
                        </div>
                    </div>

                    <!-- End At Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="end_at" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.availability_hour_end_at") }}
                        </label>
                        <div class="col-md-9">
                            <div class="input-group timepicker end_at" data-target-input="nearest">
                                <input type="text" value="{{ $schedule->end_at }}" id="end_at" name="end_at"
                                    class="form-control datetimepicker-input"
                                    placeholder="{{ trans('lang.availability_hour_end_at_placeholder') }}"
                                    data-target=".timepicker.end_at" data-toggle="datetimepicker" autocomplete="off">
                                <div class="input-group-append" data-target=".timepicker.end_at"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fas fa-business-time"></i></div>
                                </div>
                            </div>
                            <div class="form-text text-muted">{{ trans("lang.availability_hour_end_at_help") }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Provider Id Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="provider_id" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.availability_hour_e_provider_id") }}
                        </label>
                        <div class="col-md-9">
                            <select id="provider_id" name="provider_id" class="select2 form-control">
                                @foreach($providers as $provider)
                                <option value="{{ $provider->id }}" {{$provider->id == $schedule->provider_id ?
                                    'selected' : ''}} >{{ ucwords($provider->name) }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.availability_hour_e_provider_id_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Data Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="data" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.availability_hour_data") }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" id="data" name="data" value="{{$schedule->data}}" class="form-control"
                                placeholder="{{ trans('lang.availability_hour_data_placeholder') }}">
                            <div class="form-text text-muted">{{ trans("lang.availability_hour_data_help") }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.availability_hour')}}
                </button>
                <a href="{!! route('admin.provider-schedules.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
                    {{trans('lang.cancel')}}</a>
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