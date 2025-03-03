@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.booking_status_plural')" :desc="__('lang.booking_status_desc')"
        :table_name="__('lang.booking_status_table')" :route="route('admin.booking-statuses.index')" />

    <x-admins.cards.content :name1="__('lang.booking_status_table')" :name2="__('lang.booking_status_create')"
        route1="admin.booking-statuses.index" route2="admin.booking-statuses.create" :isEditMode="true" :name3="__('lang.booking_status_edit')" :route3="['admin.booking-statuses.edit', $booking_status->id]">
        <form action="{{route('admin.booking-statuses.update', $booking_status->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Order Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="order" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.booking_status_order")</label>

                        <div class="col-md-9">
                            <input type="text" name="order" value="{{old('order',$booking_status->order)}}" id="order"
                                class="form-control" placeholder="@lang('lang.booking_status_order_placeholder')"
                                required />

                            <div class="form-text text-muted">
                                @lang("lang.booking_status_order_help")
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- status Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.booking_status_status")</label>

                        <div class="col-md-9">
                            <input type="text" name="status" value="{{old('status',$booking_status->status)}}" id="status"
                                class="form-control" placeholder="@lang('lang.booking_status_status_placeholder')"
                                required />

                            <div class="form-text text-muted">
                                @lang("lang.booking_status_status_help")
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.booking_status')}}
                </button>

                <a href="{!! route('admin.booking-statuses.index') !!}" class="btn btn-default"><i
                        class="fa fa-undo"></i>
                    {{trans('lang.cancel')}}</a>
            </div>
        </form>
    </x-admins.cards.content>

    @push('scripts_lib')
    <script src="{{asset('vendor/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('vendor/summernote/summernote.min.js')}}"></script>
    <script src="{{asset('vendor/dropzone/min/dropzone.min.js')}}"></script>
    @endpush
</x-admin-layout>