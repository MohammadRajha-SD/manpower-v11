@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.faq_category_plural')" :desc="__('lang.faq_category_desc')"
        :table_name="__('lang.faq_category_table')" :route="route('admin.faq-categories.index')" />

    <x-admins.cards.content :name1="__('lang.faq_category_table')" :name2="__('lang.faq_category_create')"
        route1="admin.faq-categories.index" route2="admin.faq-categories.create" :isEditMode="true" :name3="__('lang.faq_category_edit')" :route3="['admin.faq-categories.edit', $category->id]">
        <form action="{{route('admin.faq-categories.update', $category->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.faq_category_name")</label>

                        <div class="col-md-9">
                            <input type="text" name="name" value="{{old('name', $category->name)}}" id="name" class="form-control"
                                placeholder="@lang('lang.faq_category_name_placeholder')" required />

                            <div class="form-text text-muted">
                                @lang("lang.faq_category_name_help")
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.faq_category')}}
                </button>

                <a href="{!! route('admin.faq-categories.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
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