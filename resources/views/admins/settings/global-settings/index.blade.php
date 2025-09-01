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

    <x-admins.cards.content :name1="__('lang.app_setting_globals')" route1="admin.settings.index" :isCreateMode="false">
        <form action="{{route('admin.settings.update', 1)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- contact_email Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="contact_email" class="col-4 text-right">{{ trans("lang.app_setting_contact_email")
                            }}</label>
                        <div class="col-8">
                            <input type="text" name="contact_email" id="contact_email" class="form-control"
                                value="{{ old('contact_email', setting('contact_email')) }}"
                                placeholder="{{ trans('lang.app_setting_contact_email_placeholder') }}">
                            <small class="form-text text-muted">{{ trans("lang.app_setting_contact_email_help")
                                }}</small>
                            @error('contact_email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- contact_phone Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="contact_phone" class="col-4 text-right">{{ trans("lang.app_setting_contact_phone")
                            }}</label>
                        <div class="col-8">
                            <input type="text" name="contact_phone" id="contact_phone" class="form-control"
                                value="{{ old('contact_phone', setting('contact_phone')) }}"
                                placeholder="{{ trans('lang.app_setting_contact_phone_placeholder') }}">
                            <small class="form-text text-muted">{{ trans("lang.app_setting_contact_phone_help")
                                }}</small>
                            @error('contact_phone') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- twitter_link Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="twitter_link" class="col-4 text-right">{{ trans("lang.app_setting_twitter_link")
                            }}</label>
                        <div class="col-8">
                            <input type="text" name="twitter_link" id="twitter_link" class="form-control"
                                value="{{ old('twitter_link', setting('twitter_link')) }}"
                                placeholder="{{ trans('lang.app_setting_twitter_link_placeholder') }}">
                            <small class="form-text text-muted">{{ trans("lang.app_setting_twitter_link_help")
                                }}</small>
                            @error('twitter_link') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- facebook_link Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="facebook_link" class="col-4 text-right">{{ trans("lang.app_setting_facebook_link")
                            }}</label>
                        <div class="col-8">
                            <input type="text" name="facebook_link" id="facebook_link" class="form-control"
                                value="{{ old('facebook_link', setting('facebook_link')) }}"
                                placeholder="{{ trans('lang.app_setting_facebook_link_placeholder') }}">
                            <small class="form-text text-muted">{{ trans("lang.app_setting_facebook_link_help")
                                }}</small>
                            @error('facebook_link') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- whatsapp_link Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="whatsapp_link" class="col-4 text-right">{{ trans("lang.app_setting_whatsapp_link")
                            }}</label>
                        <div class="col-8">
                            <input type="text" name="whatsapp_link" id="whatsapp_link" class="form-control"
                                value="{{ old('whatsapp_link', setting('whatsapp_link')) }}"
                                placeholder="{{ trans('lang.app_setting_whatsapp_link_placeholder') }}">
                            <small class="form-text text-muted">{{ trans("lang.app_setting_whatsapp_link_help")
                                }}</small>
                            @error('whatsapp_link') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- instagram_link Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="instagram_link" class="col-4 text-right">{{ trans("lang.app_setting_instagram_link")
                            }}</label>
                        <div class="col-8">
                            <input type="text" name="instagram_link" id="instagram_link" class="form-control"
                                value="{{ old('instagram_link', setting('instagram_link')) }}"
                                placeholder="{{ trans('lang.app_setting_instagram_link_placeholder') }}">
                            <small class="form-text text-muted">{{ trans("lang.app_setting_instagram_link_help")
                                }}</small>
                            @error('instagram_link') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Theme Contrast Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="theme_contrast" class="col-4 text-right">{{ trans("lang.app_setting_theme_contrast")
                            }}</label>
                        <div class="col-8">
                            <select name="theme_contrast" id="theme_contrast" class="form-control">
                                <option value="dark" {{ setting('theme_contrast')=='dark' ? 'selected' : '' }}>
                                    {{ trans('lang.app_setting_dark_theme') }}
                                </option>
                                <option value="light" {{ setting('theme_contrast')=='light' ? 'selected' : '' }}>
                                    {{ trans('lang.app_setting_light_theme') }}
                                </option>
                            </select>
                            <small class="form-text text-muted">{{ trans("lang.app_setting_theme_contrast_help")
                                }}</small>
                        </div>
                    </div>

                    <!-- Theme Color Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="theme_color" class="col-4 control-label text-right">{{
                            trans("lang.app_setting_theme_color") }}</label>
                        <div class="col-8">
                            <select name="theme_color" id="theme_color" class="select2 form-control">
                                @foreach ($themes as $theme => $lang)
                                <option value="{{$theme}}" {{ setting('theme_color')==$theme ? 'selected' : '' }}>
                                    {!! $lang !!}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.app_setting_theme_color_help") }}</div>
                        </div>
                    </div>

                    <!-- Navbar Color Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="nav_color" class="col-4 control-label text-right">{{
                            trans("lang.app_setting_nav_color") }}</label>
                        <div class="col-8">
                            <select name="nav_color" id="nav_color" class="select2 form-control">
                                @foreach ($navbar_colors as $navbar_color => $lang)
                                <option value="{{$navbar_color}}" {{ setting('nav_color')==$navbar_color ? 'selected'
                                    : '' }}>
                                    {!! $lang !!}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.app_setting_nav_color_help") }}</div>
                        </div>
                    </div>

                    <!-- Logo Background Color Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="logo_bg_color" class="col-4 control-label text-right">{{
                            trans("lang.app_setting_logo_bg_color") }}</label>
                        <div class="col-8">
                            <select name="logo_bg_color" id="logo_bg_color" class="select2 form-control">
                                @foreach ($logo_bg_clrs as $logo_bg_clr => $lang)
                                <option value="{{$logo_bg_clr}}" {{ setting('logo_bg_color')==$logo_bg_clr ? 'selected'
                                    : '' }}>
                                    {!! $lang !!}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.app_setting_logo_bg_color_help") }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Image Field -->
                    <div class="form-group align-items-start d-flex flex-column flex-md-row">
                        <label for="image" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.category_image")
                        </label>

                        <div class="col-md-9">
                            @if($image_path != '')
                            <!-- Preview existing image -->
                            <div class="row g-3 mb-2">
                                <div class="col-12 text-center">
                                    <div
                                        class="border rounded p-2 bg-light shadow-sm d-flex flex-column align-items-center">
                                        <img src="{{ asset($image_path) }}" class="img-fluid rounded mb-2"
                                            style="width: 100%; height: 100px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Upload new image -->
                            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
                                <input type="file" name="app_logo" />
                            </div>

                            <div class="form-text text-muted w-50">
                                @lang("lang.category_image_help")
                            </div>
                        </div>
                    </div>

                    <div class="form-group align-items-start d-flex flex-column flex-md-row">
                        <label for="banner_img" class="col-md-3 control-label text-md-right mx-1">
                            Banner Image

                        </label>

                        <div class="col-md-9">
                            @if($banner_img != '')
                            <!-- Preview existing image -->
                            <div class="row g-3 mb-2">
                                <div class="col-12 text-center">
                                    <div
                                        class="border rounded p-2 bg-light shadow-sm d-flex flex-column align-items-center">
                                        <img src="{{ asset($banner_img) }}" class="img-fluid rounded mb-2"
                                            style="width: 100%; height: 100px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Upload new image -->
                            <div style="width: 100%" class="dropzone image" id="banner_img" data-field="image">
                                <input type="file" name="banner_img" />
                            </div>

                            <div class="form-text text-muted w-50">
                                @lang("lang.category_image_help")
                            </div>
                        </div>
                    </div>
                    <!-- Fixed Header Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="fixed_header" class="col-4 control-label text-right">{{
                            __('lang.app_setting_fixed_header') }}</label>
                        <input type="hidden" name="fixed_header" value="0">
                        <div class="col-8 icheck-{{ setting('theme_color') }}">
                            <input type="checkbox" name="fixed_header" id="fixed_header" value="1" {{
                                setting('fixed_header', true) ? 'checked' : '' }}>
                            <label for="fixed_header">{{ __('lang.app_setting_fixed_header_help') }}</label>
                        </div>
                    </div>

                    <!-- Fixed Footer Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="fixed_footer" class="col-4 control-label text-right">{{
                            __('lang.app_setting_fixed_footer') }}</label>
                        <input type="hidden" name="fixed_footer" value="0">
                        <div class="col-8 icheck-{{ setting('theme_color') }}">
                            <input type="checkbox" name="fixed_footer" id="fixed_footer" value="1" {{
                                setting('fixed_footer', true) ? 'checked' : '' }}>
                            <label for="fixed_footer">{{ __('lang.app_setting_fixed_footer_help') }}</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr />

            {{-- part 2 --}}
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- app_title Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="app_title" class="col-4 text-right">App Title</label>
                        <div class="col-8">
                            <input type="text" name="app_title" id="app_title" class="form-control"
                                value="{{ old('app_title', setting('app_title', '')) }}" placeholder="App Title...">
                        </div>
                    </div>

                    <!-- booking_title Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="booking_title" class="col-4 text-right">Booking Title</label>
                        <div class="col-8">
                            <input type="text" name="booking_title" id="booking_title" class="form-control"
                                value="{{ old('booking_title', setting('booking_title', '')) }}"
                                placeholder="Booking Title...">
                        </div>
                    </div>

                    <!-- testimonial_title Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="testimonial_title" class="col-4 text-right">Testimonial Title</label>
                        <div class="col-8">
                            <input type="text" name="testimonial_title" id="testimonial_title" class="form-control"
                                value="{{ old('testimonial_title', setting('testimonial_title', '')) }}"
                                placeholder="Testimonial Title...">
                        </div>
                    </div>

                    <!-- service_title Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="service_title" class="col-4 text-right">Service Title</label>
                        <div class="col-8">
                            <input type="text" name="service_title" id="service_title" class="form-control"
                                value="{{ old('service_title', setting('service_title', '')) }}"
                                placeholder="Service Title...">
                        </div>
                    </div>

                    <!-- return_policy_title Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="return_policy_title" class="col-4 text-right">Return Policy Title</label>
                        <div class="col-8">
                            <input type="text" name="return_policy_title" id="return_policy_title" class="form-control"
                                value="{{ old('return_policy_title', setting('return_policy_title', '')) }}"
                                placeholder="Return Policy Title...">
                        </div>
                    </div>

                    <!-- privacy_policy_title Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="privacy_policy_title" class="col-4 text-right">Privacy Policy Title</label>
                        <div class="col-8">
                            <input type="text" name="privacy_policy_title" id="privacy_policy_title"
                                class="form-control"
                                value="{{ old('privacy_policy_title', setting('privacy_policy_title', '')) }}"
                                placeholder="Privacy Policy Title...">
                        </div>
                    </div>

                    <!-- meta_description Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="meta_description" class="col-4 text-right">Meta Description</label>
                        <div class="col-8">
                            <input type="text" name="meta_description" id="meta_description" class="form-control"
                                value="{{ old('meta_description', setting('meta_description', '')) }}"
                                placeholder="Meta Description...">
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- app_subtitle Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="app_subtitle" class="col-4 text-right">App Sub Title</label>
                        <div class="col-8">
                            <input type="text" name="app_subtitle" id="app_subtitle" class="form-control"
                                value="{{ old('app_subtitle', setting('app_subtitle', '')) }}"
                                placeholder="App Sub Title...">
                        </div>
                    </div>

                    <!-- booking_subtitle Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="booking_subtitle" class="col-4 text-right">Booking Sub Title</label>
                        <div class="col-8">
                            <input type="text" name="booking_subtitle" id="booking_subtitle" class="form-control"
                                value="{{ old('booking_subtitle', setting('booking_subtitle', '')) }}"
                                placeholder="Booking Sub Title...">
                        </div>
                    </div>

                    <!-- testimonial_subtitle Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="testimonial_subtitle" class="col-4 text-right">Testimonial Sub Title</label>
                        <div class="col-8">
                            <input type="text" name="testimonial_subtitle" id="testimonial_subtitle"
                                class="form-control"
                                value="{{ old('testimonial_subtitle', setting('testimonial_subtitle', '')) }}"
                                placeholder="Testimonial Sub Title...">
                        </div>
                    </div>

                    <!-- service_subtitle Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="service_subtitle" class="col-4 text-right">Service Sub Title</label>
                        <div class="col-8">
                            <input type="text" name="service_subtitle" id="service_subtitle" class="form-control"
                                value="{{ old('service_subtitle', setting('service_subtitle', '')) }}"
                                placeholder="Service Sub Title...">
                        </div>
                    </div>

                    <!-- return_policy_subtitle Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="return_policy_subtitle" class="col-4 text-right">Return Policy Sub Title</label>
                        <div class="col-8">
                            <input type="text" name="return_policy_subtitle" id="return_policy_subtitle"
                                class="form-control"
                                value="{{ old('return_policy_subtitle', setting('return_policy_subtitle', '')) }}"
                                placeholder="Return Policy Sub Title...">
                        </div>
                    </div>

                    <!-- privacy_policy_subtitle Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="privacy_policy_subtitle" class="col-4 text-right">Privacy Policy Sub Title</label>
                        <div class="col-8">
                            <input type="text" name="privacy_policy_subtitle" id="privacy_policy_subtitle"
                                class="form-control"
                                value="{{ old('privacy_policy_subtitle', setting('privacy_policy_subtitle', '')) }}"
                                placeholder="Privacy Policy Sub Title...">
                        </div>
                    </div>

                    <!-- meta_keywords Field -->
                    <div class="form-group d-flex flex-column flex-md-row">
                        <label for="meta_keywords" class="col-4 text-right">Meta Keywords</label>
                        <div class="col-8">
                            <input type="text" name="meta_keywords" id="meta_keywords" class="form-control"
                                value="{{ old('meta_keywords', setting('meta_keywords', '')) }}"
                                placeholder="Meta Keywords...">
                        </div>
                    </div>

                </div>

                <div class="d-flex flex-column col-12  mx-2">
                    <!-- terms_content Field -->
                    <label for="terms_content" class="control-label">
                        Terms Content
                    </label>
                    <textarea name="terms_content" id="terms_content" class="form-control"
                        placeholder="Terms Content..." required>
                            {!!  old('terms_content', setting('terms_content', ''))  !!}
                        </textarea>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.app_setting_globals')}}
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