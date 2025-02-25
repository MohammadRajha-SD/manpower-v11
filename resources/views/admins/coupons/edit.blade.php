@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.coupon_plural')" :desc="__('lang.coupon_desc')"
        :table_name="__('lang.coupon_table')" :route="route('admin.coupons.index')" />

    <x-admins.cards.content :name1="__('lang.coupon_table')" :name2="__('lang.coupon_create')"
        route1="admin.coupons.index" route2="admin.coupons.create" :isEditMode="true" :name3="__('lang.coupon_edit')"
        :route3="['admin.coupons.edit', $coupon->id]">

        <form action="{{route('admin.coupons.update', $coupon->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Code Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="code" class="col-md-3 control-label text-md-right mx-1">
                            {{__("lang.coupon_code")}}</label>
                        <div class="col-md-9">
                            <input type="text" name="code" value="{{old('code', $coupon->code)}}" class="form-control"
                                placeholder="{{ __(" lang.coupon_code_placeholder") }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.coupon_code_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Discount Type Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="discount_type"
                            class="col-md-3 control-label text-md-right mx-1">{{trans("lang.coupon_discount_type")
                            }}</label>
                        <div class="col-md-9">
                            <select name="discount_type" class="select2 form-control">
                                <option value="percent" {{old('discount_type', $coupon->discount_type)=='percent' ?
                                    'selected' : '' }}>{{
                                    trans('lang.coupon_percent') }}</option>
                                <option value="fixed" {{old('discount_type', $coupon->discount_type)=='fixed' ?
                                    'selected' : '' }}>{{
                                    trans('lang.coupon_fixed') }}</option>
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.coupon_discount_type_help") }}</div>
                        </div>
                    </div>

                    <!-- Discount Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="discount" class="col-md-3 control-label text-md-right mx-1">{{
                            __("lang.coupon_discount") }}</label>
                        <div class="col-md-9">
                            <input type="number" value="{{old('discount', $coupon->discount)}}" name="discount"
                                class="form-control" placeholder="{{ __(" lang.coupon_discount_placeholder") }}"
                                step="any" min="0">
                            <div class="form-text text-muted">{!! trans("lang.coupon_discount_help") !!}
                            </div>
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="description" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.coupon_description") }}</label>
                        <div class="col-md-9">
                            <textarea placeholder="{{ trans(" lang.coupon_description_placeholder") }}"
                                name="description" class="form-control">
                                {!! old('description', $coupon->description) !!}
                                </textarea>
                            <div class="form-text text-muted">{{ trans("lang.coupon_description_help") }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- EService Id Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="services" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.coupon_e_service_id") }}</label>
                        <div class="col-md-9">
                            <select name="services[]" class="select2 form-control" multiple="multiple">
                                @foreach($services as $service)
                             
                                <option value="{{ $service->id }}" {{in_array($service->id ,old('services',
                                    $coupon->services()->pluck('discountable_id')->toArray() ?? [])) ?
                                    'selected' :
                                    '' }}>
                                    {{ ucwords($service->name) }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.coupon_e_service_id_help") }}</div>
                        </div>
                    </div>

                    <!-- EProvider Id Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="providers" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.coupon_e_provider_id") }}</label>
                        <div class="col-md-9">
                            <select name="providers[]" class="select2 form-control" multiple="multiple">
                                @foreach($providers as $provider)
                                <option value="{{ $provider->id }}" {{in_array($provider->id ,old('providers',
                                    $coupon->providers()->pluck('discountable_id')->toArray() ?? [])) ?
                                    'selected' :
                                    '' }}>
                                    {{ ucwords($provider->name) }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.coupon_e_provider_id_help") }}</div>
                        </div>
                    </div>

                    <!-- Category Id Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="categories"
                            class="col-md-3 control-label text-md-right mx-1">{{trans("lang.coupon_category_id")
                            }}</label>
                        <div class="col-md-9">
                            <select name="categories[]" class="select2 form-control" multiple="multiple">
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{in_array($category->id ,old('categories',
                                    $coupon->categories()->pluck('discountable_id')->toArray() ?? [])) ?
                                    'selected' :
                                    '' }}>
                                    {{ ucwords($category->name) }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.coupon_category_id_help") }}</div>
                        </div>
                    </div>

                    <!-- Start At Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="expires_at" class="col-md-3 control-label text-md-right mx-1">{{
                            __("lang.coupon_expires_at") }}</label>
                        <div class="col-md-9">
                            <div class="input-group datepicker expires_at" data-target-input="nearest">
                                <input type="text" name="expires_at" class="form-control datetimepicker-input"
                                    placeholder="{{ __(" lang.coupon_expires_at_placeholder") }}"
                                    data-target=".datepicker.expires_at" data-toggle="datetimepicker" autocomplete="off"
                                    value="{{old('expires_at', $coupon->expires_at)}}">
                                <div id="widgetParentId"></div>
                                <div class="input-group-append" data-target=".datepicker.expires_at"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fas fa-business-time"></i></div>
                                </div>
                            </div>
                            <div class="form-text text-muted">{{ __("lang.coupon_expires_at_help") }}</div>
                        </div>
                    </div>

                    <!-- Boolean Enabled Field -->
                    <div class="d-flex flex-row  justify-content-start align-items-center">
                        <label for="enabled" class="control-label my-0 mx-3">
                            <?= trans("lang.coupon_enabled"); ?>
                        </label>
                        <input type="hidden" name="enabled" value="0" id="hidden_enabled">
                        <span class="icheck-<?= setting('theme_color'); ?>">
                            <input type="checkbox" name="enabled" value="1" id="enabled" {{old('enabled',
                                $coupon->enabled)=='1'
                            ? 'checked' : '' }}>
                            <label for="enabled"></label>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.coupon')}}
                </button>
                <a href="{!! route('admin.coupons.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
                    {{trans('lang.cancel')}}</a>
            </div>
        </form>
    </x-admins.cards.content>

    @push('scripts_lib')
    <script src="{{asset('vendor/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('vendor/summernote/summernote.min.js')}}"></script>
    <script src="{{asset('vendor/dropzone/min/dropzone.min.js')}}"></script>
    <script src="{{asset('vendor/moment/moment.min.js')}}"></script>
    <script src="{{asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    @endpush
</x-admin-layout>