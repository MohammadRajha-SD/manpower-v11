@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.most_populars_plural')" :desc="__('lang.most_popular_desc')"
        :table_name="__('lang.most_popular_edit')" :route="route('admin.most-populars.index')" />

    <x-admins.cards.content :name1="__('lang.most_popular_table')" :name2="__('lang.most_popular_create')"
        route1="admin.most-populars.index" route2="admin.most-populars.create" :name3="__('lang.most_popular_edit')"
        :route3="['admin.most-populars.edit', $most_popular->id]" :isEditMode="true">

        <form action="{{route('admin.most-populars.update', $most_popular->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.category_name")</label>

                        <div class="col-md-9">
                            <input type="text" name="name" id="name" class="form-control" value="{{$most_popular->name}}"
                                placeholder="@lang('lang.category_name_placeholder')" required />

                            <div class="form-text text-muted">
                                @lang("lang.category_name_help")
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
                                {!! $most_popular->desc !!}
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
                                @if($most_popular->image)
                                <div class="col-md-4 col-sm-6 text-center">
                                    <div
                                        class="border rounded p-2 bg-light shadow-sm d-flex flex-column align-items-center">

                                        <img src="{{ asset('uploads/'.$most_popular->image->path) }}" class="img-fluid rounded mb-2"
                                            style="width: 75px; height: 75px; object-fit: cover;">
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Upload new images -->
                            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
                                <input type="file" name="image" />
                            </div>

                            <div class="form-text text-muted w-50">
                                @lang("lang.category_image_help")
                            </div>
                        </div>

                    </div>

                    <!-- Parent Id Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="category_id" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.category")
                        </label>

                        <div class="col-md-9">
                            <select name="category_id" class="select2 not-required form-control"
                                data-empty="@lang('lang.category')">
                                <option value="">@lang('lang.category_placeholder')</option>
                                @foreach ($categories as $value)
                                <option value="{{ $value->id }}" {{$most_popular->category_id == $value->id ? 'selected' :
                                    ''}}>{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Submit Field -->
                <div
                    class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                    <button type="submit"
                        class="btn bg-{{ setting('theme_color') }} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                        <i class="fa fa-save"></i> {{ trans('lang.save') }} {{ trans('lang.most_popular') }}
                    </button>
                    <a href="{{ route('admin.most-populars.index') }}" class="btn btn-default">
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