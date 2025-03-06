@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.user')" :desc="__('lang.user_table')" :table_name="__('lang.user_table')"
        :route="route('admin.users.index')" />

    <x-admins.cards.content :name1="__('lang.user_table')" :name2="__('lang.user_create')" route1="admin.users.index"
        route2="admin.users.create" :isEditMode="true" :route3="['admin.users.edit', $user->id]" :name3="__('lang.user_edit')">

        <form action="{{route('admin.users.update', $user->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.tax_name") }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="{{ trans('lang.tax_name_placeholder') }}"
                                value="{{ old('name',$user->name) }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.tax_name_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- username Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.username") }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="username" id="username" class="form-control"
                                placeholder="{{ trans('lang.tax_name_placeholder') }}"
                                value="{{ old('username',$user->usernam) }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.user_name_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="form-group row">
                        <label for="email" class="col-md-3 col-form-label text-md-right">{{ trans('lang.user_email')
                            }}</label>
                        <div class="col-md-9">
                            <input type="text" id="email" name="email" class="form-control"
                                placeholder="{{ trans('lang.user_email_placeholder') }}"
                                value="{{ old('email',$user->email) }}">
                            <small class="form-text text-muted">{{ trans('lang.user_email_help') }}</small>
                        </div>
                    </div>

                    <!-- Phone Number Field -->
                    <div class="form-group row">
                        <label for="phone_number" class="col-md-3 col-form-label text-md-right">{{
                            trans('lang.user_phone_number') }}</label>
                        <div class="col-md-9">
                            <input type="text" id="phone_number" name="phone_number" class="form-control"
                                placeholder="{{ trans('lang.user_phone_number_placeholder') }}"
                                value="{{ old('phone_number', $user->phone_number) }}">
                            <small class="form-text text-muted">{{ trans('lang.user_phone_number_help') }}</small>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group row">
                        <label for="password" class="col-md-3 col-form-label text-md-right">{{
                            trans('lang.user_password') }}</label>
                        <div class="col-md-9">
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="{{ trans('lang.user_password_placeholder') }}">
                            <small class="form-text text-muted">{{ trans('lang.user_password_help') }}</small>
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
                            @if($user->image()->exists())
                            <div class="row g-3 mb-2">
                                <div class="col-md-4 col-sm-6 text-center">
                                    <div
                                        class="border rounded p-2 bg-light shadow-sm d-flex flex-column align-items-center">
                                        <img src="{{ asset($user->image->path) }}" class="img-fluid rounded mb-2"
                                            style="width: 75px; height: 75px; object-fit: cover;">
                                        <button type="button" class="btn btn-danger btn-sm delete-image"
                                            data-id="{{ $image->id }}" style="width: 80%; max-width: 100px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Upload new images -->
                            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
                                <input type="file" name="image" />
                            </div>

                            <div class="form-text text-muted w-50">
                                @lang("lang.category_image_help")
                            </div>
                        </div>
                    </div>


                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="parent_id" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.is_admin")
                        </label>

                        <div class="col-md-9">
                            <select name="is_admin" class="select2 form-control">
                                <option value="1" {{old('is_admin' , $user->is_admin)==1 ? 'selected' : ''
                                    }}>@lang('lang.yes')</option>
                                <option value="0" {{old('is_admin' , $user->is_admin)==0 ? 'selected' : ''
                                    }}>@lang('lang.no')</option>
                            </select>

                            <div class="form-text text-muted">
                                @lang("lang.category_parent_id_help")
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Field -->
                <div
                    class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                    <button type="submit"
                        class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                        <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.user')}}
                    </button>
                    <a href="{!! route('admin.users.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
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