@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    {{--
    <x-admins.cards.header :name="__('lang.category_plural')" :desc="__('lang.category_desc')"
        :table_name="__('lang.category_table')" :route="route('admin.categories.index')" /> --}}
    <x-admins.cards.header :name="__('lang.e_provider_type_plural')" :desc="__('lang.e_provider_type_desc')"
        :table_name="__('lang.e_provider_type_edit')" :route="route('admin.providers.index')" />

    <x-admins.cards.content :name3="__('lang.e_provider_type_edit')" :isEditMode="true"
        :route3="['admin.provider_types.edit', $provider_type->id]"
        :name1="__('lang.e_provider_type_table')" :name2="__('lang.e_provider_type_create')"
        route1="admin.provider-types.index" route2="admin.provider-types.create">
        <form action="{{route('admin.provider-types.update', $provider_type->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.e_provider_type_name")</label>

                        <div class="col-md-9">
                            <input type="text" value="{{$provider_type->name}}" name="name" id="name"
                                class="form-control" placeholder="@lang('lang.e_provider_type_name_placeholder')"
                                required />

                            <div class="form-text text-muted">
                                @lang("lang.e_provider_type_name_help")
                            </div>
                        </div>
                    </div>

                    <!-- Commission Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="commission" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.e_provider_type_commission")</label>

                        <div class="col-md-9">
                            <input type="number" value="{{$provider_type->commission}}" name="commission"
                                id="commission" class="form-control"
                                placeholder="@lang('lang.e_provider_type_commission_help')" required />

                            <div class="form-text text-muted">
                                @lang("lang.e_provider_type_commission_help")
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Disabled Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="disabled" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.e_provider_type_disabled")</label>

                        <input type="hidden" name="disabled" value="0" id="hidden_disabled">
                        <div class="col-9 icheck-{{ setting('theme_color') }}">
                            <input type="checkbox" name="disabled" value="1" {{$provider_type->disabled == 1 ? 'checked'
                            : ''}} id="disabled">
                            <label for="disabled"></label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.e_provider_type')}}
                </button>
                <a href="{!! route('admin.provider-types.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
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