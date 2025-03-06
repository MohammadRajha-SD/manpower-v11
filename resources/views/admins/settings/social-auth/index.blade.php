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
        <form action="{{route('admin.social-auth.update', 1)}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                @foreach(['facebook','twitter','google'] as $social)
                <h5 class="col-12 pb-4 @if(!$loop->first) custom-field-container @endif">
                    <i class="mr-3 fas fa-{{ $social }}"></i>{{ trans('lang.app_setting_' . $social) }}
                </h5>

                <!-- Boolean enable_<social> Field -->
                <div class="form-group row col-12">
                    <label for="enable_{{ $social }}" class="col-2 control-label text-right">{{
                        trans('lang.app_setting_enable_' . $social) }}</label>
                    <div class="checkbox icheck">
                        <label class="w-100 ml-2 form-check-inline">
                            <input type="hidden" name="enable_{{ $social }}" value="0">
                            <input type="checkbox" name="enable_{{ $social }}" value="1" {{ setting('enable_' . $social,
                                false) ? 'checked' : '' }}>
                            <span class="ml-2">{{ trans('lang.app_setting_enable_' . $social . '_help') }}</span>
                        </label>
                    </div>
                </div>

                <!-- <social>_app_id Field -->
                <div class="form-group row col-6">
                    <label for="{{ $social }}_app_id" class="col-4 control-label text-right">{{
                        trans('lang.app_setting_' . $social . '_app_id') }}</label>
                    <div class="col-8">
                        <input type="text" name="{{ $social }}_app_id" value="{{ setting($social . '_app_id') }}"
                            class="form-control"
                            placeholder="{{ trans('lang.app_setting_' . $social . '_app_id_placeholder') }}">
                        <div class="form-text text-muted">
                            {{ trans('lang.app_setting_' . $social . '_app_id_help') }}
                        </div>
                    </div>
                </div>

                <!-- <social>_app_secret Field -->
                <div class="form-group row col-6">
                    <label for="{{ $social }}_app_secret" class="col-4 control-label text-right">{{
                        trans('lang.app_setting_' . $social . '_app_secret') }}</label>
                    <div class="col-8">
                        <input type="text" name="{{ $social }}_app_secret"
                            value="{{ setting($social . '_app_secret') }}" class="form-control"
                            placeholder="{{ trans('lang.app_setting_' . $social . '_app_secret_placeholder') }}">
                        <div class="form-text text-muted">
                            {{ trans('lang.app_setting_' . $social . '_app_secret_help') }}
                        </div>
                    </div>
                </div>

                <hr>
                @endforeach
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.app_setting_social')}}
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