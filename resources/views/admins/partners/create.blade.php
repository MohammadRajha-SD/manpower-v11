@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.partner_plural')" :desc="__('lang.partner_desc')"
        :table_name="__('lang.partner_table')" :route="route('admin.partners.index')" />

    <x-admins.cards.content :name1="__('lang.partner_table')" :name2="__('lang.partner_create')"
        route1="admin.partners.index" route2="admin.partners.create">
        <form action="{{route('admin.partners.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Title Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="title" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.hero_title")</label>

                        <div class="col-md-9">
                            <input type="text" name="title" value="{{old('title')}}" id="title" class="form-control"
                                placeholder="@lang('lang.hero_title_placeholder')" required />

                            <div class="form-text text-muted">
                                @lang("lang.hero_title_help")
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
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <label for="enabled" class="control-label my-0 mx-3">{!! __('lang.hero_enabled') !!}</label>
                    <input type="hidden" name="enabled" value="0" id="hidden_enabled">
                    <span class="icheck-{{ setting('theme_color') }}">
                        <input type="checkbox" name="enabled" value="1" id="enabled">
                        <label for="enabled"></label>
                    </span>
                </div>

              
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.partner')}}
                </button>

                <a href="{!! route('admin.partners.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
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