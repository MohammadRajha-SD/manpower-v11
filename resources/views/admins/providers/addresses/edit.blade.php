@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.address_plural')" :desc="__('lang.address_desc')"
        :table_name="__('lang.address_table')" :route="route('admin.addresses.index')" />

    <x-admins.cards.content :name1="__('lang.address_table')" :name2="__('lang.address_create')"
        route1="admin.addresses.index" route2="admin.addresses.create"  :isEditMode="true" :name3="__('lang.address_edit')" :route3="['admin.addresses.edit', $address->id]">

        <form action="{{route('admin.addresses.update', $address->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Description Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="description" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.address_description") }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="description" id="description" class="form-control"
                                placeholder="{{ trans('lang.address_description_placeholder') }}"
                                value="{{ old('description', $address->description) }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.address_description_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Address Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="address" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.address_address") }}
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="address" id="address-input" class="form-control map-input"
                                placeholder="{{ trans('lang.address_address_placeholder') }}"
                                value="{{ old('address',$address->address) }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.address_address_help") }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Latitude Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="latitude" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.address_latitude") }}
                        </label>
                        <div class="col-md-9">
                            <input type="number" name="latitude" id="latitude" class="form-control" step="any"
                                placeholder="{{ trans('lang.address_latitude_placeholder') }}"
                                value="{{ old('latitude', $address->latitude) }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.address_latitude_help") }}
                            </div>
                        </div>
                    </div>

                    <!-- Longitude Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="longitude" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.address_longitude") }}
                        </label>
                        <div class="col-md-9">
                            <input type="number" name="longitude" id="longitude" class="form-control" step="any"
                                placeholder="{{ trans('lang.address_longitude_placeholder') }}"
                                value="{{ old('longitude', $address->longitude) }}">
                            <div class="form-text text-muted">
                                {{ trans("lang.address_longitude_help") }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12" style="height:400px; ">
                    <div style="width: 100%; height: 100%" id="map"></div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <label for="default" class="control-label my-0 mx-3">{{ trans("lang.address_default") }}</label>
                    <input type="hidden" name="default" value="0" id="hidden_default">
                    <span class="icheck-{{ setting('theme_color') }}">
                        <input type="checkbox" name="default" value="1" id="default" {{$address->default == "1" ? 'checked' : ''}}>
                        <label for="default"></label>
                    </span>
                </div>

                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.address')}}
                </button>
                <a href="{!! route('admin.addresses.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
                    {{trans('lang.cancel')}}</a>
            </div>
        </form>
    </x-admins.cards.content>
    @push('scripts')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_maps_key') }}&libraries=places&callback=initializeGoogleMaps">
    </script>
    @endpush
    @push('scripts_lib')
    <script src="{{asset('vendor/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('vendor/summernote/summernote.min.js')}}"></script>
    <script src="{{asset('vendor/dropzone/min/dropzone.min.js')}}"></script>
    @endpush
</x-admin-layout>