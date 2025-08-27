@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.category_plural')" :desc="__('lang.category_desc')"
        :table_name="__('lang.category_edit')" :route="route('admin.categories.index')" />

    <x-admins.cards.content :name1="__('lang.category_table')" :name2="__('lang.category_create')"
        route1="admin.categories.index" route2="admin.categories.create" :name3="__('lang.category_edit')"
        :route3="['admin.categories.edit', $category->id]" :isEditMode="true">

        <form action="{{route('admin.categories.update', $category->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.category_name")</label>

                        <div class="col-md-9">
                            <input type="text" name="name" id="name" class="form-control" value="{{$category->name}}"
                                placeholder="@lang('lang.category_name_placeholder')" required />

                            <div class="form-text text-muted">
                                @lang("lang.category_name_help")
                            </div>
                        </div>
                    </div>

                    <!-- Color Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="color" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.category_color")</label>

                        <div class="col-md-9">
                            <input type="text" name="color" id="color" value="{{$category->color}}" class="form-control"
                                placeholder="@lang('lang.category_color_placeholder')" required />

                            <div class="form-text text-muted">
                                @lang("lang.category_color_help")
                            </div>
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="description" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.category_description")</label>

                        <div class="col-md-9">
                            <textarea name="description" id="description" class="form-control"
                                placeholder="@lang('lang.category_description_placeholder')" required>
                                {!! $category->desc !!}
                            </textarea>

                            <div class="form-text text-muted">@lang("lang.category_description_help")</div>
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
                                @foreach($category->images as $image)
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

                    <!-- Order Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="order" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.category_order")
                        </label>

                        <div class="col-md-9">
                            <input type="number" name="order" id="name" class="form-control"
                                value="{{$category->order}}" placeholder="@lang('lang.category_order_placeholder')"
                                step="1" min="0" required />

                            <div class="form-text text-muted">
                                @lang("lang.category_order_help")
                            </div>
                        </div>
                    </div>

                    <!-- Parent Id Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="parent_id" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.category_parent_id")
                        </label>

                        <div class="col-md-9">
                            <select name="parent_id" class="select2 not-required form-control"
                                data-empty="@lang('lang.category_parent_id_placeholder')">
                                <option value="">@lang('lang.category_parent_id_placeholder')</option>
                                @foreach ($parentCategory as $value)
                                <option value="{{ $value->id }}" {{$category->parent_id == $value->id ? 'selected' :
                                    ''}}>{{ $value->name }}</option>
                                @endforeach
                            </select>

                            <div class="form-text text-muted">
                                @lang("lang.category_parent_id_help")
                            </div>
                        </div>
                    </div>

                         <!-- Providers Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="provider_id" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.e_provider")
                        </label>

                        <div class="col-md-9">
                            <select name="provider_id" class="select2 not-required form-control"
                                data-empty="@lang('lang.e_provider')">
                                <option value="">@lang('lang.e_provider_name_placeholder')</option>
                                @foreach ($providers as $value)
                                <option value="{{ $value->id }}" {{$category->provider_id == $value->id ? 'selected' :
                                    ''}}>{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Submit Field -->
                <div
                    class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <label for="featured" class="control-label my-0 mx-3">{!! __('lang.category_featured_help')
                            !!}</label>
                        <input type="hidden" name="featured" value="0" id="hidden_featured">
                        <span class="icheck-{{ setting('theme_color') }}">
                            <input type="checkbox" name="featured" value="1" @if ($category->featured == 1) checked
                            @endif id="featured">
                            <label for="featured"></label>
                        </span>
                    </div>

                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <label for="is_end_sub_category" class="control-label my-0 mx-3">Is End Sub Category</label>
                        <input type="hidden" name="is_end_sub_category" value="0" id="hidden_is_end_sub_category">
                        <span class="icheck-{{ setting('theme_color') }}">
                            <input type="checkbox" name="is_end_sub_category" value="1" @if ($category->is_end_sub_category == 1) checked
                            @endif id="is_end_sub_category">
                            <label for="is_end_sub_category"></label>
                        </span>
                    </div>
                    <button type="submit"
                        class="btn bg-{{ setting('theme_color') }} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                        <i class="fa fa-save"></i> {{ trans('lang.save') }} {{ trans('lang.category') }}
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-default">
                        <i class="fa fa-undo"></i> {{ trans('lang.cancel') }}
                    </a>
                </div>
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