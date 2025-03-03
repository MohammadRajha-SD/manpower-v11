@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.payment_method_plural')" :desc="__('lang.payment_method_desc')"
        :table_name="__('lang.payment_method_table')" :route="route('admin.payment-methods.index')" />

    <x-admins.cards.content :name1="__('lang.payment_method_table')" :name2="__('lang.payment_method_create')"
        route1="admin.payment-methods.index" route2="admin.payment-methods.create" :isEditMode="true"
        :name3="__('lang.payment_method_edit')" :route3="['admin.payment-methods.edit', $payment_method->id]">
        <form action="{{route('admin.payment-methods.update', $payment_method->id)}}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Image Field -->
                    <div class="form-group align-items-start d-flex flex-column flex-md-row">
                        <label for="image" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.category_image")
                        </label>

                        <div class="col-md-9">
                            <!-- Preview existing images -->
                            @if($payment_method->image()->exists())
                            <div class="row g-3 mb-2">
                                <div class="col-md-4 col-sm-6 text-center">
                                    <div
                                        class="border rounded p-2 bg-light shadow-sm d-flex flex-column align-items-center">
                                        <img src="{{ asset('uploads/'.$payment_method->image->path) }}"
                                            class="img-fluid rounded mb-2"
                                            style="width: 75px; height: 75px; object-fit: cover;">
                                        <button type="button" class="btn btn-danger btn-sm delete-image"
                                            data-id="{{ $payment_method->image->id }}"
                                            style="width: 80%; max-width: 100px;">
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

                    <!-- Name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">
                            @lang('lang.payment_method_name')
                        </label>
                        <div class="col-md-9">
                            <input type="text" id="name" name="name" value="{{$payment_method->name}}"
                                class="form-control" placeholder="@lang('lang.payment_method_name_placeholder')">
                            <div class="form-text text-muted">
                                @lang('lang.payment_method_name_help')
                            </div>
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="description" class="col-md-3 control-label text-md-right mx-1">
                            @lang('lang.payment_method_description')
                        </label>
                        <div class="col-md-9">
                            <input type="text" id="description" value="{{$payment_method->description}}"
                                name="description" class="form-control"
                                placeholder="@lang('lang.payment_method_description_placeholder')">
                            <div class="form-text text-muted">
                                @lang('lang.payment_method_description_help')
                            </div>
                        </div>
                    </div>

                    <!-- Route Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="route" class="col-md-3 control-label text-md-right mx-1">
                            @lang('lang.payment_method_route')
                        </label>
                        <div class="col-md-9">
                            <input type="text" id="route" value="{{$payment_method->route}}" name="route"
                                class="form-control" placeholder="@lang('lang.payment_method_route_placeholder')">
                            <div class="form-text text-muted">
                                @lang('lang.payment_method_route_help')
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Order Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="order" class="col-md-3 control-label text-md-right mx-1">
                            @lang('lang.payment_method_order')
                        </label>
                        <div class="col-md-9">
                            <input type="number" name="order" value="{{$payment_method->order}}" id="order"
                                class="form-control" min="0"
                                placeholder="@lang('lang.payment_method_order_placeholder')">
                            <div class="form-text text-muted">
                                @lang('lang.payment_method_order_help')
                            </div>
                        </div>
                    </div>

                    <!-- Boolean Default Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="default" class="col-md-3 control-label text-md-right mx-1">
                            @lang('lang.payment_method_default')
                        </label>
                        <input type="hidden" name="default" value="0" id="hidden_default">
                        <div class="col-9 icheck-{{ setting('theme_color') }}">
                            <input type="checkbox" name="default" id="default" value="1" {{$payment_method->default == 1
                            ? 'checked' : ''}}>
                            <label for="default"></label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.payment_method')}}
                </button>

                <a href="{!! route('admin.payment-methods.index') !!}" class="btn btn-default"><i
                        class="fa fa-undo"></i>
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