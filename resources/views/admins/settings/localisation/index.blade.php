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

    <x-admins.cards.content :name1="__('lang.app_setting_localisation')" route1="admin.localisation.index" :isCreateMode="false">
        <form action="{{route('admin.localisation.update', 1)}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- date_format Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="date_format" class="col-4 control-label text-right">{{
                            trans('lang.app_setting_date_format') }}</label>
                        <div class="col-8">
                            <input type="text" name="date_format" value="{{ setting('date_format') }}"
                                class="form-control"
                                placeholder="{{ trans('lang.app_setting_date_format_placeholder') }}">
                            <div class="form-text text-muted">
                                {!! trans('lang.app_setting_date_format_help') !!}
                            </div>
                        </div>
                    </div>

                    <!-- 'Boolean is_human_date_format Field' -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="is_human_date_format" class="col-4 control-label text-right">{{
                            trans('lang.app_setting_is_human_date_format') }}</label>
                        <div class="checkbox icheck">
                            <label class="col-8 ml-2 form-check-inline">
                                <input type="hidden" name="is_human_date_format" value="0">
                                <input type="checkbox" name="is_human_date_format" value="1" {{
                                    setting('is_human_date_format', false) ? 'checked' : '' }}>
                            </label>
                        </div>
                    </div>

                    <!-- Lang Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="language" class="col-4 control-label text-right">{{
                            trans('lang.app_setting_language') }}</label>
                        <div class="col-8">
                            <select name="language" class="select2 form-control">
                                @foreach ($languages as $language)
                                <option value="{{ $language['code'] }}" {{ setting('language')==$language['code'] ? 'selected' : '' }}>{{ $language['name']
                                    }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans('lang.app_setting_language_help') }}</div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column col-sm-12 col-md-6">

                    <!-- timezone Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="timezone" class="col-4 control-label text-right">
                            {{ trans('lang.app_setting_timezone') }}
                        </label>
                        <div class="col-8">
                            <select name="timezone" class="select2 form-control">
                                @foreach ($groupedTimezones as $group => $timezones)
                                <optgroup label="{{ $group }}">
                                    @foreach ($timezones as $timezone)
                                    <option value="{{ $timezone }}" {{ setting('timezone')==$timezone ? 'selected' : ''
                                        }}>
                                        {{ $timezone }}
                                    </option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans('lang.app_setting_timezone_help') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.app_setting_localisation')}}
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