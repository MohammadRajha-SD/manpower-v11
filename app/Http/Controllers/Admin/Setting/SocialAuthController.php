<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SocialAuthController extends Controller
{
    public function index()
    {
        return view('admins.settings.social-auth.index');
    }

    public function update(Request $request, string $id)
    {
        $socials = $this->validate($request, [
            'enable_facebook' => 'required',
            'facebook_app_id' => 'required',
            'facebook_app_secret' => 'required',
            'enable_twitter' => 'required',
            'twitter_app_id' => 'required',
            'twitter_app_secret' => 'required',
            'enable_google' => 'required',
            'google_app_id' => 'required',
            'google_app_secret' => 'required',
        ]);

        foreach ($socials as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.social-auth.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.app_setting_social')]));
    }
}
