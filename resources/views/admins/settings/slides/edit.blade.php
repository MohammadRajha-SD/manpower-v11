@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.slide_plural')" :desc="__('lang.slide_desc')"
        :table_name="__('lang.slide_table')" :route="route('admin.slides.index')" />

    <x-admins.cards.content :name1="__('lang.slide_table')" :name2="__('lang.slide_create')" route1="admin.slides.index"
        route2="admin.slides.create" :isEditMode="true" :name3="__('lang.slide_edit')"
        :route3="['admin.slides.edit', $slide->id]">

        <form action="{{route('admin.slides.update', $slide->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">

                    <!-- Text Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="text" class="col-md-3 control-label text-md-right mx-1">{{ trans("lang.slide_text")
                            }}</label>
                        <div class="col-md-9">
                            <input type="text" id="text" name="text" class="form-control"
                                placeholder="{{ trans('lang.slide_text_placeholder') }}"
                                value="{{ old('text', $slide->text) }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.slide_text_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="description" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.e_provider_description") }}</label>
                        <div class="col-md-9">
                            <textarea id="description" name="description" class="form-control"
                                placeholder="{{ trans('lang.e_provider_description_placeholder') }}">{{ old('description', $slide->desc) }}</textarea>
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
                            @if($slide->image()->exists())
                            <div class="row g-3 mb-2">
                                <div class="col-12 text-center">
                                    <div
                                        class="border rounded p-2 bg-light shadow-sm d-flex flex-column align-items-center">
                                        <img src="{{ asset('uploads/'.$slide->image->path) }}"
                                            class="img-fluid rounded mb-2"
                                            style="width: 100%; height: 150px; object-fit: cover;">
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
                        <label for="status" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.status")
                        </label>

                        <div class="col-md-9">
                            <select name="status" class="select2 form-control">
                                <option value="1" {{old('status', $slide->status)===1 ? 'selected' : ''
                                    }}>@lang('lang.yes')</option>
                                <option value="0" {{old('status' , $slide->status)===0 ? 'selected' : ''
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
                        <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.slide')}}
                    </button>
                    <a href="{!! route('admin.slides.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
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