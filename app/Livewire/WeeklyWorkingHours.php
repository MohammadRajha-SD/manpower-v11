<?php

namespace App\Livewire;

use App\Models\Provider;
use App\Models\ProviderSchedule;
use Livewire\Component;
use DateTimeZone;

class WeeklyWorkingHours extends Component
{
    public $workingHours = [];
    public $days = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
    public $groupedTimezones = [];
    public $provider_id;
    public $timezone;
    public $providers;
    public $schedule_id = null;

    public function mount($id = null)
    {
        $this->providers = Provider::all();
        $timezones = DateTimeZone::listIdentifiers();
        foreach ($timezones as $timezone) {
            $group = explode('/', $timezone)[0];
            $this->groupedTimezones[$group][] = $timezone;
        }

        if ($id != null) {
            $this->schedule_id = $id;
            $schedule = ProviderSchedule::findOrFail($id);
            $this->provider_id = $schedule->provider->id;
            $this->timezone = $schedule->timezone;
            $this->workingHours = json_decode($schedule->data, true);
        } else {
            $this->provider_id = $this->providers->first()->id ?? null;
            $this->timezone = setting('timezone');

            foreach ($this->days as $day) {
                $this->workingHours[$day] = null;
            }
        }
    }

    public function checkboxUpdated($day)
    {
        if (isset($this->workingHours[$day])) {
            $this->workingHours[$day] = null;
        } else {
            $this->workingHours[$day] = ['start' => '09:00', 'end' => '18:00'];
        }
    }

    public function workingHoursUpdated($day, $key)
    {
        if (!isset($this->workingHours[$day])) {
            return;
        }

        $startTime = isset($this->workingHours[$day]['start']) ? strtotime($this->workingHours[$day]['start']) : null;
        $endTime = isset($this->workingHours[$day]['end']) ? strtotime($this->workingHours[$day]['end']) : null;

        if ($startTime && $endTime) {
            if ($endTime <= $startTime) {
                $this->workingHours[$day]['end'] = date('H:i', strtotime('+1 hour', $startTime));
                $this->dispatch('showToast', [
                    'type' => 'error',
                    'message' => "End time must be after start time for {$day}."
                ]);
            }
        }
    }


    public function submit()
    {
        // Create Mode
        if ($this->schedule_id == null) {
            ProviderSchedule::create([
                'provider_id' => $this->provider_id,
                'data' => json_encode($this->workingHours),
                'timezone' => $this->timezone,
            ]);
            return redirect()->route('admin.provider-schedules.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.availability_hour')]));
        } else {
            // Edit Mode
            ProviderSchedule::findOrFail($this->schedule_id)->update([
                'provider_id' => $this->provider_id,
                'data' => json_encode($this->workingHours),
                'timezone' => $this->timezone,
            ]);
            
            return redirect()->route('admin.provider-schedules.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.availability_hour')]));
        }

    }
    public function render()
    {
        // ->layout('admins.layouts.master')
        return view('livewire.weekly-working-hours');
    }
}