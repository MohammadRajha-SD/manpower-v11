<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use DateTimeZone;
use Illuminate\Http\Request;

class LocalisationController extends Controller
{
    public function index()
    {
        $timezones = DateTimeZone::listIdentifiers();
        $groupedTimezones = [];

        foreach ($timezones as $timezone) {
            $group = explode('/', $timezone)[0];
            $groupedTimezones[$group][] = $timezone;
        }


        $languages = avaiableLanguages();

        return view('admins.settings.localisation.index', compact('groupedTimezones', 'languages'));
    }

    public function update(Request $request, string $id)
    {
        $localisations = $this->validate($request, [
            'date_format' => 'required|string|regex:/^[A-Za-z0-9\s\(\):\-\/]+$/',
            'is_human_date_format' => 'required|boolean',
            'language' => 'required|in:en,fr,de,es,it,ar',
            'timezone' => 'required|timezone',
        ]);

        foreach ($localisations as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.localisation.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.app_setting_localisation')]));
    }
}
