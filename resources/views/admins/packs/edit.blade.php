@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.pack')" :desc="__('lang.pack_management')" :table_name="__('lang.pack')"
        :route="route('admin.packs.index')" />

    <x-admins.cards.content :name1="__('lang.pack_management')" :name2="__('lang.create_pack')"
        route1="admin.packs.index" route2="admin.packs.create" :isEditMode="true"
        :route3="['admin.packs.edit', $pack->id]" :name3="__('lang.update_pack')">

        <form action="{{route('admin.packs.update', $pack->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Text Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="text" class="col-md-3 control-label text-md-right mx-1">{{ trans("lang.slide_text")
                            }}</label>
                        <div class="col-md-9">
                            <input type="text" id="text" name="text" class="form-control" value="{{$pack->text}}"
                                placeholder="{{ trans('lang.slide_text_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.slide_text_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Text Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="short_description" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.short_description") }}</label>
                        <div class="col-md-9">

                            <input value="{{$pack->short_description}}" type="text" id="short_description"
                                name="short_description" class="form-control"
                                placeholder="{{ trans('lang.slide_text_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.slide_text_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Number of Months of SubscriptionOld -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="number_of_months" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.number_of_months") }}</label>
                        <div class="col-md-9">
                            <input type="number" value="{{$pack->number_of_months}}" id="number_of_months"
                                name="number_of_months" class="form-control"
                                placeholder="{{ trans('lang.number_of_months_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.number_of_months_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Number of Ads of Service in this Pack -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="number_of_ads" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.number_of_ads") }}</label>
                        <div class="col-md-9">
                            <input type="number" value="{{$pack->number_of_ads}}" id="number_of_ads"
                                name="number_of_ads" class="form-control"
                                placeholder="{{ trans('lang.number_of_ads_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.number_of_ads_help") }}
                            </div>
                        </div>
                    </div>


                    <!-- Dropdown List -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="type" class="col-md-3 control-label text-md-right mx-1">{{ trans("lang.type")
                            }}</label>
                        <div class="col-md-9">
                            <select id="type" name="type" class="form-control">
                                <option value="{{trans('lang.companies') }}" {{$pack->type == trans('lang.companies') ?
                                    'selected' : ''}} >{{ trans('lang.companies') }}</option>
                                <option value="{{trans('lang.individuals') }}" {{$pack->type ==
                                    trans('lang.individuals') ? 'selected' : ''}} >{{ trans('lang.individuals') }}
                                </option>
                            </select>
                            <div class="form-text text-muted">
                                {{ trans("lang.type_text_help") }}
                            </div>
                        </div>
                    </div>


                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row ">
                        <label for="price" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.e_service_price") }}</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="number" value="{{$pack->price}}" name="price" id="price"
                                    class="form-control" step="any" min="0"
                                    placeholder="{{ trans('lang.e_service_price_placeholder') }}">
                                <div class="input-group-append">
                                    <div class="input-group-text text-bold px-3">{{ setting('default_currency','AED') }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-text text-muted">
                                {{ trans("lang.e_service_price_help") }}
                            </div>
                        </div>
                    </div>


                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="description" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.e_provider_description") }}</label>
                        <div class="col-md-9">
                            <textarea id="description" name="description" class="form-control"
                                placeholder="{{ trans('lang.e_provider_description_placeholder') }}">

                                {!! $pack->description !!}
                            </textarea>
                            <div class="form-text text-muted">
                                {{ trans("lang.e_provider_description_help") }}
                            </div>
                        </div>
                    </div>

                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Number of Subcategories -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="number_of_subcategories" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.number_of_subcategories") }}</label>
                        <div class="col-md-9">
                            <input type="number" value="{{$pack->number_of_subcategories}}" id="number_of_subcategories"
                                name="number_of_subcategories" class="form-control"
                                placeholder="{{ trans('lang.number_of_subcategories_placeholder') }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.number_of_subcategories_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Do not appear in the featured services section -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="not_in_featured_services" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.not_in_featured_services") }}</label>
                        <div class="col-md-9">
                            <select id="not_in_featured_services" name="not_in_featured_services" class="form-control">
                                <option value="1" {{$pack->not_in_featured_services == '1' ? 'selected' : '' }} >{{
                                    trans('lang.yes') }}</option>
                                <option value="0" {{$pack->not_in_featured_services == '0' ? 'selected' : '' }} >{{
                                    trans('lang.no') }}</option>
                            </select>
                            <div class="form-text text-muted">
                                {{ trans("lang.not_in_featured_services_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Do not display images on image slider -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="not_on_image_slider" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.not_on_image_slider") }}</label>
                        <div class="col-md-9">
                            <select id="not_on_image_slider" name="not_on_image_slider" class="form-control">
                                <option value="1" {{$pack->not_on_image_slider == '1' ? 'selected' : ''}}>{{
                                    trans('lang.yes') }}</option>
                                <option value="0" {{$pack->not_on_image_slider == '0' ? 'selected' : ''}}>{{
                                    trans('lang.no') }}</option>
                            </select>
                            <div class="form-text text-muted">
                                {{ trans("lang.not_on_image_slider_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Stripe Plan ID field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="stripe_plan_id" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.stripe_plan_id") }}</label>
                        <div class="col-md-9">
                            <input type="text" id="stripe_plan_id" name="stripe_plan_id" class="form-control"
                                value="{{ old('stripe_plan_id', $pack->stripe_plan_id) }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.stripe_plan_id_help") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.pack')}}
                </button>

                <a href="{!! route('admin.packs.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
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