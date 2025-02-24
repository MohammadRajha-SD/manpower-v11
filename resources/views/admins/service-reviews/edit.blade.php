@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.e_service_review_plural')" :desc="__('lang.e_service_review_desc')"
        :table_name="__('lang.e_service_review_table')" :route="route('admin.service-reviews.index')" />

    <x-admins.cards.content :name1="__('lang.e_service_review_table')" :name2="__('lang.e_service_review_create')"
        :isEditMode="true" :route3="['admin.service-reviews.edit', $service_review->id]" route1="admin.service-reviews.index" route2="admin.service-reviews.create"
        :name3="__('lang.e_service_review_edit')"
        >
        
        <form action="{{route('admin.service-reviews.update',$service_review->id)}}" method="post">
            @csrf
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Rate Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="rate" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.e_service_review_rate") }}</label>
                        <div class="col-md-9">
                            <select name="rate" id="rate" class="select2 form-control">
                                @foreach($rates as $rate)
                                <option value="{{ $rate }}" {{ old('rate', $service_review->rate)==$rate ? 'selected' : '' }}>{{ $rate }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.e_service_review_rate_help") }}</div>
                        </div>
                    </div>
                    <!-- User Id Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="user_id" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.e_service_review_user_id") }}</label>
                        <div class="col-md-9">
                            <select name="user_id" id="user_id" class="select2 form-control">
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $service_review->user_id)==$user->id ? 'selected' : '' }}>{{
                                    ucwords($user->name) }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.e_service_review_user_id_help") }}</div>
                        </div>
                    </div>

                    <!-- Service Id Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="service_id" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.e_service_review_e_service_id") }}</label>
                        <div class="col-md-9">
                            <select name="service_id" id="service_id" class="select2 form-control">
                                @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id', $service_review->service_id)==$service->id ? 'selected' : ''
                                    }}>{{ ucwords($service->name) }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.e_service_review_e_service_id_help") }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Review Field -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="review" class="col-md-3 control-label text-md-right mx-1">{{
                            trans("lang.e_service_review_review") }}</label>
                        <div class="col-md-9">
                            <textarea name="review" id="review" class="form-control"
                                placeholder="{{ trans('lang.e_service_review_review_placeholder') }}">{{ old('review', $service_review->review) }}</textarea>
                            <div class="form-text text-muted">{{ trans("lang.e_service_review_review_help") }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.e_service_review')}}
                </button>
                <a href="{!! route('admin.service-reviews.index') !!}" class="btn btn-default"><i
                        class="fa fa-undo"></i>
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