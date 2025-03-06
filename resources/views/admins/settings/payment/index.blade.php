@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.app_setting')" :desc="__('lang.setting_desc')"
        :route="route('admin.settings.index')" />

    <x-admins.cards.content :name1="__('lang.app_setting_payment')" route1="admin.setting-payment.index"
        :isCreateMode="false">
        <form action="{{route('admin.setting-payment.update', 1)}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Default Tax Field -->
                <div class="form-group row col-6">
                    <label for="default_tax" class="col-4 control-label text-right">{{
                        trans('lang.app_setting_default_tax') }}</label>
                    <div class="col-8">
                        <input type="text" id="default_tax" name="default_tax" class="form-control"
                            placeholder="{{ trans('lang.app_setting_default_tax_placeholder') }}"
                            value="{{ setting('default_tax') }}">
                        <div class="form-text text-muted">
                            {{ trans('lang.app_setting_default_tax_help') }}
                        </div>
                    </div>
                </div>

                <!-- Default Currency Field -->
                <div class="form-group row col-6">
                    <label for="default_currency_id" class="col-4 control-label text-right">{{
                        trans('lang.app_setting_default_currency') }}</label>
                    <div class="col-8">
                        <select id="default_currency_id" name="default_currency_id" class="select2 form-control">
                            @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}" {{ setting('default_currency_id', 1)== $currency->id ?
                                'selected' : '' }}>
                                {{ $currency->name }} -  {{ $currency->symbol }}
                            </option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted">{{ trans('lang.app_setting_default_currency_help') }}
                        </div>
                    </div>
                </div>

                <!-- Currency Right Field -->
                <div class="form-group row col-6">
                    <label for="currency_right" class="col-4 control-label text-right">{{
                        trans('lang.app_setting_currency_right') }}</label>
                    <div class="checkbox icheck">
                        <label class="w-100 ml-2 form-check-inline">
                            <input type="hidden" name="currency_right" value="0">
                            <input type="checkbox" id="currency_right" name="currency_right" value="1" {{
                                setting('currency_right', false) ? 'checked' : '' }}>
                            <span class="ml-2">{{ trans('lang.app_setting_currency_right_help') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.app_setting_payment')}}
                </button>

                <a href="{!! route('admin.dashboard') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
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