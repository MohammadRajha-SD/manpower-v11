@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.currency')" :desc="__('lang.currency_desc')"
        :table_name="__('lang.currency_table')" :route="route('admin.currencies.index')" />

    <x-admins.cards.content :name1="__('lang.currency_table')" :name2="__('lang.currency_create')"
        route1="admin.currencies.index" route2="admin.currencies.create">
        <form action="{{route('admin.currencies.store')}}" method="post">
            @csrf
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">{{ __('lang.currency_name')
                            }}</label>
                        <div class="col-md-9">
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="{{ __('lang.currency_name_placeholder') }}" value="{{ old('name') }}">
                            <div class="form-text text-muted">
                                {{ __('lang.currency_name_help') }}
                            </div>
                        </div>
                    </div>

                    <!-- Symbol Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="symbol" class="col-md-3 control-label text-md-right mx-1">{{
                            __('lang.currency_symbol') }}</label>
                        <div class="col-md-9">
                            <input type="text" id="symbol" name="symbol" class="form-control"
                                placeholder="{{ __('lang.currency_symbol_placeholder') }}" value="{{ old('symbol') }}">
                            <div class="form-text text-muted">
                                {{ __('lang.currency_symbol_help') }}
                            </div>
                        </div>
                    </div>

                    <!-- Code Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="code" class="col-md-3 control-label text-md-right mx-1">{{ __('lang.currency_code')
                            }}</label>
                        <div class="col-md-9">
                            <input type="text" id="code" name="code" class="form-control"
                                placeholder="{{ __('lang.currency_code_placeholder') }}" value="{{ old('code') }}">
                            <div class="form-text text-muted">
                                {{ __('lang.currency_code_help') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Decimal Digits Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="decimal_digits" class="col-md-3 control-label text-md-right mx-1">{{
                            __('lang.currency_decimal_digits') }}</label>
                        <div class="col-md-9">
                            <input type="number" id="decimal_digits" name="decimal_digits" class="form-control"
                                placeholder="{{ __('lang.currency_decimal_digits_placeholder') }}"
                                value="{{ old('decimal_digits') }}">
                            <div class="form-text text-muted">
                                {{ __('lang.currency_decimal_digits_help') }}
                            </div>
                        </div>
                    </div>

                    <!-- Rounding Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="rounding" class="col-md-3 control-label text-md-right mx-1">{{
                            __('lang.currency_rounding') }}</label>
                        <div class="col-md-9">
                            <input type="number" id="rounding" name="rounding" class="form-control"
                                placeholder="{{ __('lang.currency_rounding_placeholder') }}"
                                value="{{ old('rounding') }}">
                            <div class="form-text text-muted">
                                {{ __('lang.currency_rounding_help') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.currency')}}
                </button>

                <a href="{!! route('admin.currencies.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
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