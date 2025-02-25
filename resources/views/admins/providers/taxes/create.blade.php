@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.tax_plural')" :desc="__('lang.tax_desc')" :table_name="__('lang.tax_table')"
        :route="route('admin.taxes.index')" />

    <x-admins.cards.content :name1="__('lang.tax_table')" :name2="__('lang.tax_create')" route1="admin.taxes.index"
        route2="admin.taxes.create">

        <form action="{{route('admin.taxes.store')}}" method="post">
            @csrf
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.tax_name") }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="{{ trans('lang.tax_name_placeholder') }}" value="{{ old('name') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.tax_name_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- value Field --->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="value" class="col-md-3 control-label text-md-right mx-1">{{ trans('lang.tax_value')
                            }}</label>
                        <div class="col-md-9">
                            <input type="number" value="{{old('value')}}" name="value" id="value" class="form-control"
                                step="any" min="0" placeholder="{{ trans('lang.tax_value_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans('lang.tax_value_help') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Type Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="type" class="col-md-3 control-label text-md-right mx-1">{{ trans("lang.tax_type")
                            }}</label>
                        <div class="col-md-9">
                            <select name="type" id="type" class="select2 form-control">
                                <option value="percent">{{ trans('lang.tax_percent') }}</option>
                                <option value="fixed">{{ trans('lang.tax_fixed') }}</option>
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.tax_type_help") }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.tax')}}
                </button>
                <a href="{!! route('admin.taxes.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
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