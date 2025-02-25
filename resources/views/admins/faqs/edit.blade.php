@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.faq_plural')" :desc="__('lang.faq_desc')" :table_name="__('lang.faq_table')"
        :route="route('admin.faqs.index')" />

    <x-admins.cards.content :name1="__('lang.faq_table')" :name2="__('lang.faq_create')" route1="admin.faqs.index"
        route2="admin.faqs.create">
        <form action="{{route('admin.faqs.update', $faq->id)}}" method="post" >
            @csrf
            @method('PUT')
            <!-- Faq Category Id Field -->
            <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                <label for="faq_category_id" class="col-md-3 control-label text-md-right mx-1">
                    {{ trans("lang.faq_faq_category_id") }}
                </label>
                <div class="col-md-9">
                    <select name="faq_category_id" id="faq_category_id" class="select2 form-control">
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ ucwords($category->name) }}
                        </option>
                        @endforeach
                    </select>
                    <div class="form-text text-muted">{{ trans("lang.faq_faq_category_id_help") }}</div>
                </div>
            </div>

            <!-- Question Field -->
            <div class="form-group align-items-baseline d-flex flex-md-row">
                <label for="question" class="col-md-3 control-label text-md-right mx-1">
                    {{ trans("lang.faq_question") }}
                </label>
                <div class="col-md-9">
                    <textarea name="question" id="question" class="form-control"
                        placeholder="{{ trans('lang.faq_question_placeholder') }}">
                        {!! old('question', $faq->question) !!}
                    </textarea>
                    <div class="form-text text-muted">{{ trans('lang.faq_question_help') }}</div>
                </div>
            </div>

            <!-- Answer Field -->
            <div class="form-group align-items-baseline d-flex flex-row">
                <label for="answer" class="col-md-3 control-label text-md-right mx-1">
                    {{ trans("lang.faq_answer") }}
                </label>
                <div class="col-md-9">
                    <textarea name="answer" id="answer" class="form-control"
                        placeholder="{{ trans('lang.faq_answer_placeholder') }}">
                        {!! old('answer', $faq->answer) !!}
                    </textarea>
                    <div class="form-text text-muted">{{ trans('lang.faq_answer_help') }}</div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.faq')}}
                </button>
                <a href="{!! route('admin.faqs.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
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