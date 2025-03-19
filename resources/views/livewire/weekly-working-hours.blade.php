<div class="row">

    <div class="d-flex flex-column col-sm-12 col-md-8">
        <h5>Weekly working hours</h5>

        <div class="list-group">
            @foreach($days as $day)
            <div class="list-group-item d-flex align-items-center justify-content-between">
                <div>
                    <input type="checkbox" class="me-2" id="{{ $day }}" name="{{ $day }}"
                        wire:click="checkboxUpdated('{{ $day }}')" @if(isset($workingHours[$day])) checked @endif>

                    <label for="{{ $day }}" class="me-3">{{ $day }}</label>
                </div>
                @if(isset($workingHours[$day]))
                <div class="d-flex">
                    <input type="time" class="form-control w-auto mx-1"
                        wire:change='workingHoursUpdated("{{ $day }}", "start")'
                        wire:model.lazy="workingHours.{{ $day }}.start">
                    <input type="time" class="form-control w-auto mx-1"
                        wire:change='workingHoursUpdated("{{ $day }}", "end")'
                        wire:model.lazy="workingHours.{{ $day }}.end">
                </div>
                {{-- <div>
                    <button type="button" class="btn btn-danger btn-sm me-1">&times;</button>
                    <button type="button" class="btn btn-primary btn-sm">+</button>
                </div> --}}
                @else
                <div class="justify-self-start">
                    Unavailable
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <div class="d-flex flex-column col-sm-12 col-md-4">
        <!-- timezone Field -->
        <div class="form-group  align-items-center">
            <label for="timezone" class=" control-label text-right">
                {{ trans('lang.app_setting_timezone') }}
            </label>
            <select id="timezone" wire:model='timezone' class="select2 form-control">
                @foreach ($groupedTimezones as $group => $timezones)
                <optgroup label="{{ $group }}">
                    @foreach ($timezones as $timezone)
                    <option value="{{ $timezone }}">
                        {{ $timezone }}
                    </option>
                    @endforeach
                </optgroup>
                @endforeach
            </select>
            <div class="form-text text-muted">{{ trans('lang.app_setting_timezone_help') }}</div>
        </div>

        <!-- Provider Selection -->
        <div class="form-group  align-items-center">
            <label for="provider_id" class=" col-form-label text-md-right">
                {{ trans('lang.availability_hour_e_provider_id') }}
            </label>
            <select id="provider_id" wire:model='provider_id' class="select2 form-control">
                @foreach($providers as $provider)
                <option value="{{ $provider->id }}">
                    {{ ucwords($provider->name) }}
                </option>
                @endforeach
            </select>
            <small class="form-text text-muted">
                {{ trans('lang.availability_hour_e_provider_id_help') }}
            </small>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="form-group d-flex justify-content-end border-top pt-4">
        <button type="button" wire:click='submit' class="btn bg-{{ setting('theme_color') }} mx-2">
            <i class="fa fa-save"></i> {{ trans('lang.save') }} {{ trans('lang.availability_hour') }}
        </button>
        <a href="{{ route('admin.provider-schedules.index') }}" class="btn btn-default">
            <i class="fa fa-undo"></i> {{ trans('lang.cancel') }}
        </a>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('showToast', data => {
                iziToast[data[0].type]({
                    title: data[0].type,
                    message: data[0].message,
                    position: 'topCenter',
                });
            });
          
            const providerSelect = $('#provider_id');
            providerSelect.select2();

            providerSelect.on('change', function (e) {
                const selectedValue = e.target.value; 

                @this.set('provider_id', selectedValue);
            });

            const timezone = $('#timezone');
            timezone.select2();

            timezone.on('change', function (e) {
                const timezoneValue = e.target.value; 

                @this.set('timezone', timezoneValue);
            });
        });
    </script>
</div>