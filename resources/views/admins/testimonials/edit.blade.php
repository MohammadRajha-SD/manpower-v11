@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.testimonial_plural')" :desc="__('lang.testimonial_desc')"
        :table_name="__('lang.testimonial_table')" :route="route('admin.testimonials.index')" />

    <x-admins.cards.content :name1="__('lang.testimonial_table')" :name2="__('lang.testimonial_create')"
        route1="admin.testimonials.index" route2="admin.testimonials.create" :isEditMode="true" :name3="__('lang.testimonial_edit')" :route3="['admin.testimonials.edit', $testimonial->id]">
        <form action="{{route('admin.testimonials.update', $testimonial->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.faq_category_name")</label>

                        <div class="col-md-9">
                            <input type="text" name="name" value="{{old('name', $testimonial->name)}}" id="name" class="form-control"
                                placeholder="@lang('lang.faq_category_name_placeholder')" required />

                            <div class="form-text text-muted">
                                @lang("lang.faq_category_name_help")
                            </div>
                        </div>
                    </div>

                       {{-- Description Field --}}
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="description" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.e_service_description")</label>

                        <div class="col-md-9">
                            <textarea name="description" id="description" class="form-control"
                                placeholder="@lang('lang.e_service_description_placeholder')" required>
                                {!! old('description', $testimonial->description) !!}
                            </textarea>

                            <div class="form-text text-muted">@lang("lang.e_service_description_help")</div>
                        </div>
                    </div>

                    {{-- Stars --}}
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="price_unit" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.stars") }}</label>
                        <div class="col-md-9">
                            <select name="stars" class="select2 form-control">
                                @for ($i = 1; $i <= 5; $i++) <option value="{{ $i }}" {{ $testimonial->stars == $i ? 'selected' : '' }}>{{ $i }} ★</option>
                                    @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.testimonial')}}
                </button>

                <a href="{!! route('admin.testimonials.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
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