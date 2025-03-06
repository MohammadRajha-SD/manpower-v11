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

    <x-admins.cards.content :name1="(__('lang.app_setting_smtp')) . getActiveMailDriver('smtp')"
        route1="admin.mails.smtp" :name2="(__('lang.app_setting_mailgun')) .  getActiveMailDriver('mailgun')"
        route2="admin.mails.mailgun" :isEditMode="true"
        :name3="(__('lang.app_setting_sparkpost')) .  getActiveMailDriver('sparkpost')"
        :route3="['admin.mails.sparkpost', 1]">
        <form action="{{route('admin.mails.mailgun.update', 1)}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <input type="hidden" name="mail_driver" value="mailgun">

                    <!-- mailgun_domain Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="mailgun_domain" class="col-4 control-label text-right">
                            {{ trans("lang.app_setting_mailgun_domain") }}
                        </label>
                        <div class="col-8">
                            <input type="text" name="mailgun_domain" value="{{ setting('mailgun_domain') }}"
                                class="form-control"
                                placeholder="{{ trans('lang.app_setting_mailgun_domain_placeholder') }}">
                            <div class="form-text text-muted">
                                {!! trans("lang.app_setting_mailgun_domain_help") !!}
                            </div>
                        </div>
                    </div>

                    <!-- mailgun_secret Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="mailgun_secret" class="col-4 control-label text-right">
                            {{ trans("lang.app_setting_mailgun_secret") }}
                        </label>
                        <div class="col-8">
                            <input type="text" name="mailgun_secret" value="{{ setting('mailgun_secret') }}"
                                class="form-control"
                                placeholder="{{ trans('lang.app_setting_mailgun_secret_placeholder') }}">
                            <div class="form-text text-muted">
                                {!! trans("lang.app_setting_mailgun_secret_help") !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.app_setting_mailgun')}}
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