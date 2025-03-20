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
        route1="admin.providers.index" route2="admin.providers.create" :isEditMode="true"
        :name3="__('lang.e_provider_edit')" :route3="['admin.providers.edit', $provider->id]">
        <form action="{{route('admin.providers.update', $provider->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.e_provider_name")</label>

                        <div class="col-md-9">
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{old('name',$provider->name)}}"
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
                            <input type="text" name="email" id="email" class="form-control" value="{{old('email', $provider->email)}}"
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
                                <option value="{{ $provider_type->id }}" {{$provider_type->id==
                                    old('provider_type_id',$provider->provider_type_id) ?'selected' :''}}>{{ ucwords(
                                    $provider_type->name) }}</option>
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
                                <option value="{{ $user->id }}" {{ in_array($user->id, old('users',
                                    $provider->users->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
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
                                {!!old('description', $provider->description)!!}
                            </textarea>
                            <div class="form-text text-muted">{{ trans("lang.e_provider_description_help") }}</div>
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
                            <!-- Preview existing images -->
                            <div class="row g-3 mb-2">
                                @foreach($provider->images as $image)
                                <div class="col-md-4 col-sm-6 text-center">
                                    <div
                                        class="border rounded p-2 bg-light shadow-sm d-flex flex-column align-items-center">
                                        <img src="{{ asset($image->path) }}" class="img-fluid rounded mb-2"
                                            style="width: 75px; height: 75px; object-fit: cover;">
                                        <button type="button" class="btn btn-danger btn-sm delete-image"
                                            data-id="{{ $image->id }}" style="width: 80%; max-width: 100px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Upload new images -->
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
                            <input type="text" name="phone_number"
                                value="{{old('phone_number', $provider->phone_number)}}" id="phone_number"
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
                            <input type="text" value="{{old('mobile_number', $provider->mobile_number)}}"
                                name="mobile_number" id="mobile_number" class="form-control"
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
                                <option value="{{ $address->id }}" {{ in_array($address->id, old('addresses',
                                    $provider->addresses->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>

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
                                <option value="{{ $tax->id }}" {{ in_array($tax->id, old('taxes',
                                    $provider->taxes->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
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
                                <input type="number"
                                    value="{{old('availability_range', $provider->availability_range)}}"
                                    name="availability_range" id="availability_range" class="form-control" step="any"
                                    min="0" placeholder="{{ trans('lang.e_provider_availability_range_placeholder') }}">
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
                        <input type="checkbox" name="accepted" {{ $provider->accepted == 1 ? 'checked' : '' }} value="1"
                        id="accepted" >
                        <label for="accepted"></label>
                    </span>
                </div>

                <div class="d-flex flex-row justify-content-between align-items-center">
                    <label for="available" class="control-label my-0 mx-3">
                        <?= trans("lang.e_provider_available"); ?>
                    </label>
                    <input type="hidden" name="available1" value="0" id="hidden_available">
                    <span class="icheck-<?= setting('theme_color'); ?>">
                        <input type="checkbox" name="available" {{ $provider->available == 1 ? 'checked' : '' }}
                        value="1" id="available">
                        <label for="available"></label>
                    </span>
                </div>

                <div class="d-flex flex-row justify-content-between align-items-center">
                    <label for="featured" class="control-label my-0 mx-3">
                        <?= trans("lang.e_provider_featured"); ?>
                    </label>
                    <input type="hidden" name="featured" value="0" id="hidden_featured">
                    <span class="icheck-<?= setting('theme_color'); ?>">
                        <input type="checkbox" name="featured" {{ $provider->featured == 1 ? 'checked' : '' }} value="1"
                        id="featured">
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
    {{-- LOGIC TO DELETE THE IMAGE --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
         $(".delete-image").on("click", function() {
             let imageId = $(this).data("id");
 
             // Dynamically generate the route URL
             let deleteUrl = "{{ route('admin.image.delete', ['id' => '__imageId__']) }}";
             deleteUrl = deleteUrl.replace('__imageId__', imageId); // Replace placeholder with actual imageId

             Swal.fire({
                 title: "Are you sure?",
                 text: "You won't be able to revert this!",
                 icon: "warning",
                 showCancelButton: true,
                 confirmButtonColor: "#d33",
                 cancelButtonColor: "#3085d6",
                 confirmButtonText: "Yes, delete it!"
             })
             .then((result) => {
                 if (result.isConfirmed) {
                     $.ajax({
                         url: deleteUrl,
                         type: "DELETE",
                         data: {
                             _token: $("meta[name='csrf-token']").attr("content")
                         },
                         success: function(data) {
                             if (data.success) {
                                 Swal.fire({
                                     icon: 'success',
                                     title: 'Success!',
                                     text: data.message,
                                     confirmButtonText: 'OK'
                                 }).then(()=>{
                                     window.location.reload();
                                 });
                             } else {
                                 Swal.fire({
                                     icon: 'danger',
                                     title: 'Error!',
                                     text: "Could not delete image.",
                                     confirmButtonText: 'OK'
                                 });
                             }
                         },
                         error: function(xhr, status, error) {
                             Swal.fire({
                                 icon: 'danger',
                                 title: 'Error!',
                                 text: "An error occurred while trying to delete the image.",
                                 confirmButtonText: 'OK'
                             });
                         }
                     });
                 }
             });
         });

     });
    </script>
    @push('scripts_lib')
    <script src="{{asset('vendor/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('vendor/summernote/summernote.min.js')}}"></script>
    <script src="{{asset('vendor/dropzone/min/dropzone.min.js')}}"></script>
    @endpush
</x-admin-layout>