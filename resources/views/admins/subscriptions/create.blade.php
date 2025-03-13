@push('css_lib')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
<x-admin-layout>
    <x-admins.cards.header :name="__('lang.subscriptions')" :desc="__('lang.subscription_list')"
        :route="route('admin.subscriptions.index')" />

    <x-admins.cards.content :name1="__('lang.subscription_list')" route1="admin.subscriptions.index"
        route2="admin.subscriptions.create" :name2="__('lang.create_subscription')">
        <form action="{{route('admin.subscriptions.store')}}" method="post">
            @csrf
            <div class="row">
                <div class="d-flex flex-column col-sm-12 col-md-6">
                    <!-- Pack Id -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="pack_id" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.pack") }}
                        </label>
                        <div class="col-md-9">
                            <select name="pack_id" id="pack_id" class="select2 form-control">
                                <option value="" selected> {{ __('lang.select') }} </option>
                                @foreach($packs as $pack)
                                <option value="{{ $pack->id }}" {{ old('pack_id')==$pack->id ? 'selected' : '' }}>
                                    {{ ucwords($pack->text) }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.pack") }}</div>
                        </div>
                    </div>

                    <!-- Provider Id -->
                    <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="provider_id" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.e_provider") }}
                        </label>
                        <div class="col-md-9">
                            <select name="provider_id" id="provider_id" class="select2 form-control">
                                <option value="" selected> {{ __('lang.select') }} </option>
                                @foreach($providers as $provider)
                                <option value="{{ $provider->id }}" {{ old('provider_id')==$provider->id ? 'selected' :
                                    ''
                                    }}>
                                    {{ ucwords($provider->name) }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.provider_name") }}</div>
                        </div>
                    </div>

                  
                </div>

                <div class="d-flex flex-column col-sm-12 col-md-6">
                    {{-- <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="status" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.stripe_status") }}
                        </label>
                        <div class="col-md-9">
                            <select name="status" id="status" class="select2 form-control">
                                @foreach(['disabled','active', 'incomplete'] as $status)
                                <option value="{{ $status }}" {{ old('status')==$status ? 'selected' : '' }}>
                                    {{ ucwords($status) }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.status") }}</div>
                        </div>
                    </div> --}}
                      <!-- User Id -->
                      <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                        <label for="user_id" class="col-md-3 control-label text-md-right mx-1">
                            {{ trans("lang.user") }}
                        </label>
                        <div class="col-md-9">
                            <select name="user_id" id="user_id" class="select2 form-control">
                                <option value="" selected> {{ __('lang.select') }} </option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id')==$user->id ? 'selected' : '' }}>
                                    {{ ucwords($user->name) }} - {{ $user->email }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">{{ trans("lang.user") }}</div>
                        </div>
                    </div>
                </div>
                
            </div>

            <!-- Submit Field -->
            <div
                class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                <button type="submit" class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                    <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.subscriptions')}}
                </button>

                <a href="{!! route('admin.subscriptions.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
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