<x-admin-layout>
    <x-admins.cards.header :name="__('lang.user_profile')" :desc="__('lang.media_desc')"
        :route="route('admin.user.profile')" />

    <x-admins.profile-section>
        <form action="{{route('admin.user.profile.update')}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">


                <div class="d-flex flex-column col-sm-12 col-md-6">

                    <!-- Name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.user_name") }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" id="name" name="name" value="{{old('name', auth()->user()->name)}}"
                                class="form-control" placeholder="{{ trans('lang.user_name_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.user_name_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="email" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.user_email") }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" id="email" value="{{old('email', auth()->user()->email)}}" name="email"
                                class="form-control" placeholder="{{ trans('lang.user_email_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.user_email_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Phone Number Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="phone_number" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.user_phone_number") }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" id="phone_number"
                                value="{{old('phone_number', auth()->user()->phone_number)}}" name="phone_number"
                                class="form-control" placeholder="{{ trans('lang.user_phone_number_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.user_phone_number_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Password Field -->
                    {{-- <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="password" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.user_password") }}
                        </label>
                        <div class="col-md-9">
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="{{ trans('lang.user_password_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.user_password_help") }}
                            </div>
                        </div>
                    </div> --}}
                </div>

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
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fas fa-save"></i> {{trans('lang.save')}} {{trans('lang.user')}}</button>
                <a href="{!! route('admin.dashboard') !!}" class="btn btn-default"><i class="fas fa-undo"></i>
                    {{trans('lang.cancel')}}</a>
            </div>
        </form>
    </x-admins.profile-section>
</x-admin-layout>