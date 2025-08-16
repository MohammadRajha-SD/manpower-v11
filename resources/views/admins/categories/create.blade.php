@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>


    <x-admins.cards.header :name="__('lang.category_plural')" :desc="__('lang.category_desc')"
        :table_name="__('lang.category_table')" :route="route('admin.categories.index')" />

    <x-admins.cards.content :name1="__('lang.category_table')" :name2="__('lang.category_create')"
        route1="admin.categories.index" route2="admin.categories.create">

        <form action="{{route('admin.categories.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.category_name")</label>

                        <div class="col-md-9">
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="@lang('lang.category_name_placeholder')" required/>

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
                            <input type="text" name="color" id="color" class="form-control"
                                placeholder="@lang('lang.category_color_placeholder')"required />

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
                                placeholder="@lang('lang.category_order_placeholder')" step="1" min="0" required/>

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
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
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
                    <div class="d-flex flex-row justify-content-between align-items-center">
                        <label for="featured" class="control-label my-0 mx-3">{!! __('lang.category_featured_help') !!}</label>
                        <input type="hidden" name="featured" value="0" id="hidden_featured">
                        <span class="icheck-{{ setting('theme_color') }}">
                            <input type="checkbox" name="featured" value="1" id="featured">
                            <label for="featured"></label>
                        </span>
                    </div>
                     <div class="d-flex flex-row justify-content-between align-items-center">
                        <label for="is_end_sub_category" class="control-label my-0 mx-3">Is End Sub Category</label>
                        <input type="hidden" name="is_end_sub_category" value="0" id="hidden_is_end_sub_category">
                        <span class="icheck-{{ setting('theme_color') }}">
                            <input type="checkbox" name="is_end_sub_category" value="1" id="is_end_sub_category">
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

    @push('scripts_lib')
    <script src="{{asset('vendor/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('vendor/summernote/summernote.min.js')}}"></script>
    <script src="{{asset('vendor/dropzone/min/dropzone.min.js')}}"></script>
    @endpush
</x-admin-layout>