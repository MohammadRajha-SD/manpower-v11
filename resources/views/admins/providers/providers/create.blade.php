@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.e_provider_plural')" :desc="__('lang.e_provider_desc')"
        :table_name="__('lang.e_provider_table')" :route="route('admin.providers.index')" />

    <x-admins.cards.content :name1="__('lang.e_provider_table')" :name2="__('lang.e_provider_create')"
        route1="admin.providers.index" route2="admin.providers.create">
        <form action="{{route('admin.providers.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.e_provider_name")</label>

                        <div class="col-md-9">
                            <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}"
                                placeholder="@lang('lang.e_provider_name_placeholder')" required />

                            <div class="form-text text-muted">
                                @lang("lang.e_provider_name_help")
                            </div>
                        </div>
                    </div> 
                    <!-- EMAIL Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="email" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.email")</label>

                        <div class="col-md-9">
                            <input type="text" name="email" id="email" class="form-control" value="{{old('email')}}"
                                placeholder="@lang('lang.email')" required />

                            <div class="form-text text-muted">
                                @lang("lang.email")
                            </div>
                        </div>
                    </div>

                    <!-- Provider Type Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="provider_type_id" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.e_provider_e_provider_type_id") }}
                        </label>
                        <div class="col-md-9">
                            <select name="provider_type_id" id="provider_type_id" class="select2 form-control">
                                @foreach($provider_types as $provider_type)
                                <option value="{{ $provider_type->id }}">{{ ucwords( $provider_type->name) }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.e_provider_e_provider_type_id_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Users Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="users" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.e_provider_users") }}
                        </label>
                        <div class="col-md-9">
                            <select name="users[]" id="users" class="select2 form-control">
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{in_array($user->id ,old('users', [])) ? 'selected' :
                                    '' }}>
                                    {{ ucwords($user->name) }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.e_provider_users_help") }}</div>
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="description" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.e_provider_description") }}
                        </label>
                        <div class="col-md-9">
                            <textarea name="description" id="description" class="form-control"
                                placeholder="{{ trans('lang.e_provider_description_placeholder') }}">
                                {!!old('description')!!}
                            </textarea>
                            <div class="form-text text-muted">{{ trans("lang.e_provider_description_help") }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Image Field -->
                    <div class="form-group align-items-start d-flex flex-column flex-md-row">
                        <label for="images" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.category_image")
                        </label>

                        <div class="col-md-9">
                            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
                                <input type="file" name="images[]" multiple />
                            </div>

                            <div class="form-text text-muted w-50">
                                @lang("lang.category_image_help")
                            </div>
                        </div>
                    </div>

                    <!-- Phone Number Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="phone_number" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.e_provider_phone_number") }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" value="{{old('phone_number')}}" name="phone_number" id="phone_number"
                                class="form-control"
                                placeholder="{{ trans('lang.e_provider_phone_number_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.e_provider_phone_number_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Number Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="mobile_number" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.e_provider_mobile_number") }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" value="{{old('mobile_number')}}" name="mobile_number" id="mobile_number"
                                class="form-control"
                                placeholder="{{ trans('lang.e_provider_mobile_number_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.e_provider_mobile_number_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Addresses Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="addresses" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.e_provider_addresses") }}
                        </label>
                        <div class="col-md-9">
                            <select name="addresses[]" id="addresses" class="select2 form-control" multiple>
                                @foreach($addresses as $address)
                                <option value="{{ $address->id }}" {{ in_array($address->id, old('addresses', [])) ?
                                    'selected' : '' }}>
                                    {{ ucwords($address->address) }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">
                                {{ trans("lang.e_provider_addresses_help") }}
                                <a href="{{ route('admin.addresses.create') }}" class="text-success float-right">{{
                                    __('lang.address_create') }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- Taxes Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="taxes" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.e_provider_taxes") }}
                        </label>
                        <div class="col-md-9">
                            <select name="taxes[]" id="taxes" class="select2 form-control" multiple>
                                @foreach($taxes as $tax)
                                <option value="{{ $tax->id }}" {{ in_array($tax->id, old('taxes', [])) ? 'selected' : ''
                                    }}>
                                    {{ ucwords($tax->name) }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">
                                {{ trans("lang.e_provider_taxes_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Availability Range Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="availability_range" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.e_provider_availability_range") }}
                        </label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="number" name="availability_range" id="availability_range"
                                    class="form-control" step="any" min="0" value="{{old('availability_range')}}"
                                    placeholder="{{ trans('lang.e_provider_availability_range_placeholder') }}">
                                <div class="input-group-append">
                                    <div class="input-group-text text-bold px-3">
                                        {{ trans("lang.app_setting_" . setting('distance_unit', 'mi')) }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-text text-muted">
                                {{ trans("lang.e_provider_availability_range_help") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <label for="accepted" class="control-label my-0 mx-3">
                        <?= trans("lang.e_provider_accepted"); ?>
                    </label>
                    <input type="hidden" name="accepted" value="0" id="hidden_accepted">
                    <span class="icheck-<?= setting('theme_color'); ?>">
                        <input type="checkbox" name="accepted" value="1" id="accepted">
                        <label for="accepted"></label>
                    </span>
                </div>

                <div class="d-flex flex-row justify-content-between align-items-center">
                    <label for="available" class="control-label my-0 mx-3">
                        <?= trans("lang.e_provider_available"); ?>
                    </label>
                    <input type="hidden" name="available1" value="0" id="hidden_available">
                    <span class="icheck-<?= setting('theme_color'); ?>">
                        <input type="checkbox" name="available" value="1" id="available">
                        <label for="available"></label>
                    </span>
                </div>

                <div class="d-flex flex-row justify-content-between align-items-center">
                    <label for="featured" class="control-label my-0 mx-3">
                        <?= trans("lang.e_provider_featured"); ?>
                    </label>
                    <input type="hidden" name="featured" value="0" id="hidden_featured">
                    <span class="icheck-<?= setting('theme_color'); ?>">
                        <input type="checkbox" name="featured" value="1" id="featured">
                        <label for="featured"></label>
                    </span>
                </div>

                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.e_provider')}}
                </button>
                <a href="{!! route('admin.providers.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
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