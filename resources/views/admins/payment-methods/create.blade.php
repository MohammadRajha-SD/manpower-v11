@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.payment_method_plural')" :desc="__('lang.payment_method_desc')"
        :table_name="__('lang.payment_method_table')" :route="route('admin.payment-methods.index')" />

    <x-admins.cards.content :name1="__('lang.payment_method_table')" :name2="__('lang.payment_method_create')"
        route1="admin.payment-methods.index" route2="admin.payment-methods.create">
        <form action="{{route('admin.payment-methods.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Image Field -->
                    <div class="form-group align-items-start d-flex flex-column flex-md-row">
                        <label for="image" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.category_image")
                        </label>

                        <div class="col-md-9">
                            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
                                <input type="file" name="image" />
                            </div>

                            <div class="form-text text-muted w-50">
                                @lang("lang.category_image_help")
                            </div>
                        </div>
                    </div>

                    <!-- Name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">
                            @lang('lang.payment_method_name')
                        </label>
                        <div class="col-md-9">
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="@lang('lang.payment_method_name_placeholder')">
                            <div class="form-text text-muted">
                                @lang('lang.payment_method_name_help')
                            </div>
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="description" class="col-md-3 control-label text-md-right mx-1">
                            @lang('lang.payment_method_description')
                        </label>
                        <div class="col-md-9">
                            <input type="text" id="description" name="description" class="form-control"
                                placeholder="@lang('lang.payment_method_description_placeholder')">
                            <div class="form-text text-muted">
                                @lang('lang.payment_method_description_help')
                            </div>
                        </div>
                    </div>

                    <!-- Route Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="route" class="col-md-3 control-label text-md-right mx-1">
                            @lang('lang.payment_method_route')
                        </label>
                        <div class="col-md-9">
                            <input type="text" id="route" name="route" class="form-control"
                                placeholder="@lang('lang.payment_method_route_placeholder')">
                            <div class="form-text text-muted">
                                @lang('lang.payment_method_route_help')
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Order Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="order" class="col-md-3 control-label text-md-right mx-1">
                            @lang('lang.payment_method_order')
                        </label>
                        <div class="col-md-9">
                            <input type="number" name="order" id="order" class="form-control" min="0"
                                placeholder="@lang('lang.payment_method_order_placeholder')">
                            <div class="form-text text-muted">
                                @lang('lang.payment_method_order_help')
                            </div>
                        </div>
                    </div>

                    <!-- Boolean Default Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="default" class="col-md-3 control-label text-md-right mx-1">
                            @lang('lang.payment_method_default')
                        </label>
                        <input type="hidden" name="default" value="0" id="hidden_default">
                        <div class="col-9 icheck-{{ setting('theme_color') }}">
                            <input type="checkbox" name="default" id="default" value="1">
                            <label for="default"></label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.payment_method')}}
                </button>

                <a href="{!! route('admin.payment-methods.index') !!}" class="btn btn-default"><i
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