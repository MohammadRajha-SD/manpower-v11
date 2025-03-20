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
        route1="admin.addresses.index" route2="admin.addresses.create">

        <form action="{{route('admin.addresses.store')}}" method="post">
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


                    <!-- Description Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="description" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.address_description") }}</label>
                        <div class="col-md-9">
                            <textarea name="description" id="description" class="form-control"
                                placeholder="{{ trans('lang.address_description_placeholder') }}">{!! old('description') !!}</textarea>
                            <div class="form-text text-muted">{{ trans("lang.address_description_help") }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6" style="height:400px; ">
                    <div style="width: 100%; height: 100%" id="map"></div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.address')}}
                </button>
                <a href="{!! route('admin.addresses.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
                    {{trans('lang.cancel')}}</a>
            </div>
        </form>
    </x-admins.cards.content>

    @push('scripts')
    <script>
        function initMap() {
            var dubai = { lat: 25.276987, lng: 55.296249 };
        
            var map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12, 
                center: dubai
            });
        }
    </script>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_maps_key') }}&libraries=places&callback=initMap">
    </script>
    @endpush
    @push('scripts_lib')
    <script src="{{asset('vendor/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('vendor/summernote/summernote.min.js')}}"></script>
    <script src="{{asset('vendor/dropzone/min/dropzone.min.js')}}"></script>
    @endpush
</x-admin-layout>