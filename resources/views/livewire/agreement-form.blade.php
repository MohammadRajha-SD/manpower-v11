<div>
    @if($showModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('lang.agreement') }}</h5>
                    <button type="button" class="close" wire:click="closeModal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="d-flex flex-column col-sm-12 col-md-6">
                            <!-- Name Field -->
                            <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                                <label for="name" class="col-md-3 control-label text-md-right mx-1">
                                    Company Name
                                </label>

                                <div class="col-md-9">
                                    <input type="text" name="name" id="name" class="form-control" wire:model="name"
                                        placeholder="@lang('lang.e_provider_name_placeholder')" required />
                                </div>
                            </div>

                            <!-- EMAIL Field -->
                            <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                                <label for="email" class="col-md-3 control-label text-md-right mx-1">
                                    @lang("lang.email")
                                </label>

                                <div class="col-md-9">
                                    <input type="text" name="email" id="email" class="form-control" wire:model="email"
                                        placeholder="@lang('lang.email')" required />

                                    <div class="form-text text-muted">
                                        @lang("lang.email")
                                    </div>
                                </div>
                            </div>

                            <!-- Phone Number Field -->
                            <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                                <label for="phone" class="col-md-3 control-label text-md-right mx-1">
                                    {{ trans("lang.e_provider_phone_number") }}
                                </label>
                                <div class="col-md-9">
                                    <input type="text" name="phone" id="phone" class="form-control" wire:model="phone"
                                        placeholder="{{ trans('lang.e_provider_phone_number_placeholder') }}">
                                    <div class="form-text text-muted">
                                        {{ trans("lang.e_provider_phone_number_help") }}
                                    </div>
                                </div>
                            </div>

                            <!-- License Number Field -->
                            <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                                <label for="license_number" class="col-md-3 control-label text-md-right mx-1">
                                    @lang('lang.license_number')
                                </label>
                                <div class="col-md-9">
                                    <input type="text" name="license_number" id="license_number" class="form-control"
                                        wire:model="license_number" placeholder="{{ trans('lang.license_number') }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column col-sm-12 col-md-6">
                            <!-- Provider Request Field -->
                            <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                                <label for="provider_request_id" class="col-md-3 control-label text-md-right mx-1">
                                    {{ trans("lang.e_provider") }}
                                </label>
                                <div class="col-md-9">
                                    <select name="provider_request_id" id="provider_request_id"
                                        class="select2 form-control" wire:model="provider_request_id">
                                        @foreach($provider_requests as $provider_request)
                                        <option value="{{ $provider_request->id }}" {{ $provider_request->id ==
                                            old('provider_request_id') ? 'selected' : '' }}>
                                            {{ ucwords($provider_request->company_name) }} - {{
                                            $provider_request->contact_email }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- plans Field -->
                            <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                                <label for="plan_id" class="col-md-3 control-label text-md-right mx-1">
                                    {{ trans("lang.plan") }}
                                </label>
                                <div class="col-md-9">
                                    <select name="plan_id" id="plan_id" class="select2 form-control"
                                        wire:model="plan_id">
                                        @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ $plan->id == old('plan_id') ? 'selected' : ''
                                            }}>
                                            {{ ucwords($plan->text) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.stripe_plan_id_help") }}
                                    </div>
                                </div>
                            </div>

                            <!-- commission Field -->
                            <div class="form-group align-items-baseline d-flex flex-column flex-md-row">
                                <label for="commission" class="col-md-3 control-label text-md-right mx-1">
                                    {{ trans("lang.e_provider_type_commission") }}
                                </label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input type="number" name="commission" id="commission" class="form-control"
                                            wire:model="commission" step="1" min="0"
                                            placeholder="{{ trans('lang.e_provider_type_commission_placeholder') }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text text-bold px-3">
                                                %
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-text text-muted">
                                        {{ trans("lang.e_provider_type_commission_help") }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

              
                </div>
            
                <div>
                          <!-- Submit Field -->
                    <div
                        class="form-group col-12 d-flex flex-column flex-md-row justify-content-md-end justify-content-sm-center border-top pt-4">
                        {{-- <div class="d-flex flex-row justify-content-between align-items-center">
                            <label for="terms" class="control-label my-0 mx-3">
                                <?= trans("lang.terms"); ?>
                            </label>
                            <input type="hidden" name="terms" value="0" id="hidden_terms">
                            <span class="icheck-<?= setting('theme_color'); ?>">
                                <input type="checkbox" name="terms" value="1" id="terms" wire:model="terms">
                                <label for="terms"></label>
                            </span>
                        </div> --}}

                        {{-- <div class="d-flex flex-row justify-content-between align-items-center">
                            <label for="signed" class="control-label my-0 mx-3">
                                <?= trans("lang.signed"); ?>
                            </label>
                            <input type="hidden" name="signed" value="0" id="hidden_signed">
                            <span class="icheck-<?= setting('theme_color'); ?>">
                                <input type="checkbox" name="signed" value="1" id="signed" wire:model="signed">
                                <label for="signed"></label>
                            </span>
                        </div> --}}

                        <button wire:click="submit"
                            class="btn bg-{{setting('theme_color')}} mx-md-3 my-lg-0 my-xl-0 my-md-0 my-2">
                            <i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.agreement')}}
                        </button>
                        <a wire:click="closeModal" href="#" class="btn btn-default"><i class="fa fa-undo"></i>
                            {{trans('lang.close')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>