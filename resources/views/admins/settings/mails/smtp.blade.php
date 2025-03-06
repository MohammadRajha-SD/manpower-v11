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
        <form action="{{route('admin.mails.smtp.update', 1)}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- 'Boolean enable_facebook Field' -->
                <div class="col-12">
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="enable_email_notifications" class="col-2 control-label text-right">
                            {{ trans('lang.app_setting_enable_email_notifications') }}
                        </label>

                        <input type="hidden" name="enable_email_notifications" value="0"
                            id="hidden_enable_email_notifications">

                        <div class="col-10 icheck-{{ setting('theme_color') }}">
                            <input type="checkbox" id="enable_email_notifications" name="enable_email_notifications"
                                value="1" {{ setting('enable_email_notifications', false) ? 'checked' : '' }}>
                            <label for="enable_email_notifications">
                                {{ trans('lang.app_setting_enable_email_notifications_help') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <input type="hidden" name="mail_driver" value="smtp">

                    <!-- mail_host Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="mail_host" class="col-4 control-label text-right">
                            {{ trans("lang.app_setting_mail_host") }}
                        </label>
                        <div class="col-8">
                            <input type="text" name="mail_host" id="mail_host" value="{{ setting('mail_host') }}"
                                class="form-control" placeholder="{{ trans(" lang.app_setting_mail_host_placeholder")
                                }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.app_setting_mail_host_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- mail_port Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="mail_port" class="col-4 control-label text-right">
                            {{ trans("lang.app_setting_mail_port") }}
                        </label>
                        <div class="col-8">
                            <input type="text" name="mail_port" id="mail_port" value="{{ setting('mail_port') }}"
                                class="form-control" placeholder="{{ trans(" lang.app_setting_mail_port_placeholder")
                                }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.app_setting_mail_port_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- mail_encryption Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="mail_encryption" class="col-4 control-label text-right">
                            {{ trans("lang.app_setting_mail_encryption") }}
                        </label>
                        <div class="col-8">
                            <select name="mail_encryption" id="mail_encryption" class="select2 form-control">
                                <option value="tls" {{ setting('mail_encryption')=='tls' ? 'selected' : '' }}>TLS
                                </option>
                                <option value="ssl" {{ setting('mail_encryption')=='ssl' ? 'selected' : '' }}>SSL
                                </option>
                            </select>
                            <div class="form-text text-muted">
                                {{ trans("lang.app_setting_mail_encryption_help") }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- mail_username Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="mail_username" class="col-4 control-label text-right">
                            {{ trans("lang.app_setting_mail_username") }}
                        </label>
                        <div class="col-8">
                            <input type="text" name="mail_username" id="mail_username"
                                value="{{ setting('mail_username') }}" class="form-control"
                                placeholder="{{ trans('lang.app_setting_mail_username_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.app_setting_mail_username_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- mail_password Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="mail_password" class="col-4 control-label text-right">
                            {{ trans("lang.app_setting_mail_password") }}
                        </label>
                        <div class="col-8">
                            <input type="password" name="mail_password" id="mail_password" class="form-control"
                                placeholder="{{ trans('lang.app_setting_mail_password_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.app_setting_mail_password_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- mail_from_address Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="mail_from_address" class="col-4 control-label text-right">
                            {{ trans("lang.app_setting_mail_from_address") }}
                        </label>
                        <div class="col-8">
                            <input type="text" name="mail_from_address" id="mail_from_address"
                                value="{{ setting('mail_from_address') }}" class="form-control"
                                placeholder="{{ trans('lang.app_setting_mail_from_address_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.app_setting_mail_from_address_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- mail_from_name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="mail_from_name" class="col-4 control-label text-right">
                            {{ trans("lang.app_setting_mail_from_name") }}
                        </label>
                        <div class="col-8">
                            <input type="text" name="mail_from_name" id="mail_from_name"
                                value="{{ setting('mail_from_name') }}" class="form-control"
                                placeholder="{{ trans('lang.app_setting_mail_from_name_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.app_setting_mail_from_name_help") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.app_setting_smtp')}}
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