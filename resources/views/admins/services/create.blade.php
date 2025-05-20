@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.e_service_plural')" :desc="__('lang.e_service_desc')"
        :table_name="__('lang.e_service_table')" :route="route('admin.services.index')" />

    <x-admins.cards.content :name1="__('lang.e_service_table')" :name2="__('lang.e_service_create')"
        route1="admin.services.index" route2="admin.services.create">
        <form action="{{route('admin.services.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Address Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="address" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.address_address") }}
                        </label>
                        <div class="col-md-9">
                            <select name="address" class="form-control select2">
                                @foreach (config('emirates') as $emirate => $cities)
                                <optgroup label="{{ $emirate }}">
                                    @foreach ($cities as $city)
                                    <option value="{{ $city['slug'] }}">{{ $city['name'] }}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>

                            <div class="form-text text-muted">
                                {{ trans("lang.address_address_help") }}
                            </div>
                        </div>
                    </div>
                    
             
                    <!-- Name Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="name" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.e_service_name") }}</label>
                        <div class="col-md-9">
                            <input type="text" name="name" class="form-control"
                                placeholder="{{ trans('lang.e_service_name_placeholder') }}" value="{{ old('name') }}"
                                required>
                            <div class="form-text text-muted">
                                {{ trans("lang.e_service_name_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Categories Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="category_id" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.e_service_categories") }}</label>
                        <div class="col-md-9">
                            <select name="category_id" class="select2 form-control not-required"
                                data-empty="{{ trans('lang.e_service_categories_placeholder') }}" multiple="multiple">
                                <option value="" disabled>Select</option>

                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id== old('category_id')? 'selected' :
                                    '' }}>{{ ucwords($category->name) }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.e_service_categories_help") }}</div>
                        </div>
                    </div>

                    <!-- Price Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="price" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.e_service_price") }}</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="number" name="price" class="form-control" step="any" min="0"
                                    placeholder="{{ trans('lang.e_service_price_placeholder') }}"
                                    value="{{ old('price') }}" required>
                                <div class="input-group-append">
                                    <div class="input-group-text text-bold px-3">{{ setting('default_currency', '$') }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-text text-muted">{{ trans("lang.e_service_price_help") }}</div>
                        </div>
                    </div>
    
                    <!-- QTY LIMIT Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="qty_limit" class="col-md-3 control-label text-md-right mx-1">Qty Limit</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="number" name="qty_limit" class="form-control" step="any" min="0"
                                    placeholder="Enter qty limit"
                                    value="{{ old('qty_limit') }}" required>
                            </div>
                            
                        </div>
                    </div>

                    <!-- Discount Price Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="discount_price" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.e_service_discount_price") }}</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="number" name="discount_price" class="form-control" step="any" min="0"
                                    placeholder="{{ trans('lang.e_service_discount_price_placeholder') }}"
                                    value="{{ old('discount_price',0) }}">
                                <div class="input-group-append">
                                    <div class="input-group-text text-bold px-3">{{ setting('default_currency', '$') }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-text text-muted">{{ trans("lang.e_service_discount_price_help") }}</div>
                        </div>
                    </div>

                    <!-- Price Unit Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="price_unit" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.e_service_price_unit") }}</label>
                        <div class="col-md-9">
                            <select name="price_unit" class="select2 form-control">
                                <option value="hourly" {{ old('price_unit')=='hourly' ? 'selected' : '' }}>{{
                                    trans('lang.e_service_price_unit_hourly') }}</option>
                                <option value="fixed" {{ old('price_unit')=='fixed' ? 'selected' : '' }}>{{
                                    trans('lang.e_service_price_unit_fixed') }}</option>
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.e_service_price_unit_help") }}</div>
                        </div>
                    </div>

                    <!-- Quantity Unit Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="quantity_unit" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.e_service_quantity_unit") }}</label>
                        <div class="col-md-9">
                            <input type="text" name="quantity_unit" class="form-control"
                                placeholder="{{ trans('lang.e_service_quantity_unit_placeholder') }}"
                                value="{{ old('quantity_unit') }}">
                            <div class="form-text text-muted">{{ trans("lang.e_service_quantity_unit_help") }}</div>
                        </div>
                    </div>

                    <!-- Duration Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="duration" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.e_service_duration") }}</label>
                        <div class="col-md-9">
                            <div class="input-group timepicker duration" data-target-input="nearest">
                                <input type="text" name="duration" class="form-control datetimepicker-input"
                                    placeholder="{{ trans('lang.e_service_duration_placeholder') }}"
                                    data-target=".timepicker.duration" data-toggle="datetimepicker" autocomplete="off"
                                    value="{{ old('duration') }}">
                                <div id="widgetParentId"></div>
                                <div class="input-group-append" data-target=".timepicker.duration"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fas fa-business-time"></i></div>
                                </div>
                            </div>
                            <div class="form-text text-muted">{{ trans("lang.e_service_duration_help") }}</div>
                        </div>
                    </div>

                    <!-- Providers Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="provider_id" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.e_service_e_provider_id") }}</label>
                        <div class="col-md-9">
                            <select name="provider_id" id="provider_id" class="select2 form-control" required>
                                <option value="" selected disabled>------</option>
                                @foreach($providers as $provider)
                                <option value="{{ $provider->id }}" {{ old('provider_id')==$provider->id ? 'selected'
                                    : '' }}>{{ ucwords($provider->name) }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.e_service_e_provider_id_help") }}</div>
                        </div>
                    </div>
                    
                   
                    <!-- Review Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="terms" class="col-md-3 control-label text-md-right mx-1">terms & condications</label>
                        <div class="col-md-9">
                            <textarea name="terms" id="terms" class="form-control"
                                placeholder="{{ trans('lang.e_service_review_review_placeholder') }}">{!! old('terms') !!}</textarea>
                            <div class="form-text text-muted">{{ trans("lang.e_service_review_review_help") }}</div>
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

                    <!-- Description Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="description" class="col-md-3 control-label text-md-right mx-1">
                            @lang("lang.e_service_description")</label>

                        <div class="col-md-9">
                            <textarea name="description" id="description" class="form-control"
                                placeholder="@lang('lang.e_service_description_placeholder')" required>
                            </textarea>

                            <div class="form-text text-muted">@lang("lang.e_service_description_help")</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <label for="featured" class="control-label my-0 mx-3">{!! __('lang.category_featured_help')
                        !!}</label>
                    <input type="hidden" name="featured" value="0" id="hidden_featured">
                    <span class="icheck-{{ setting('theme_color') }}">
                        <input type="checkbox" name="featured" value="1" id="featured">
                        <label for="featured"></label>
                    </span>
                </div>
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <label for="enable_booking" class="control-label my-0 mx-3">{!! __('lang.e_service_enable_booking')
                        !!}</label>
                    <input type="hidden" name="enable_booking" value="0" id="hidden_enable_booking">
                    <span class="icheck-{{ setting('theme_color') }}">
                        <input type="checkbox" name="enable_booking" value="1" id="enable_booking">
                        <label for="enable_booking"></label>
                    </span>
                </div>
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.e_service')}}
                </button>
                <a href="{!! route('admin.services.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
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