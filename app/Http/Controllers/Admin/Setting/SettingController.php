<?php

namespace App\Http\Controllers\Admin\Setting;

use App\DataTables\SettingDataTable;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use ImageHandler;

    public function index()
    {
        $themes = [
            'primary' => trans('lang.app_setting_blue'),
            'secondary' => trans('lang.app_setting_gray'),
            'danger' => trans('lang.app_setting_red'),
            'warning' => trans('lang.app_setting_yellow'),
            'info' => trans('lang.app_setting_sky_blue'),
            'success' => trans('lang.app_setting_green'),
            'indigo' => trans('lang.app_setting_indigo'),
            'purple' => trans('lang.app_setting_purple'),
            'fuchsia' => trans('lang.app_setting_fuchsia'),
            'pink' => trans('lang.app_setting_pink'),
            'maroon' => trans('lang.app_setting_maroon'),
            'navy' => trans('lang.app_setting_navy'),
            'lime' => trans('lang.app_setting_lime'),
            'teal' => trans('lang.app_setting_teal'),
            'lightblue' => trans('lang.app_setting_lightblue'),
            'cyan' => trans('lang.app_setting_cyan'),
            'orange' => trans('lang.app_setting_orange'),
            'olive' => trans('lang.app_setting_olive'),
        ];

        $navbar_colors = [
            'navbar-dark navbar-primary' => trans('lang.app_setting_blue'),
            'navbar-dark navbar-gray' => trans('lang.app_setting_gray'),
            'navbar-dark navbar-dark' => trans('lang.app_setting_dark'),
            'navbar-light navbar-white' => trans('lang.app_setting_white'),
            'navbar-dark navbar-danger' => trans('lang.app_setting_red'),
            'navbar-light navbar-warning' => trans('lang.app_setting_yellow'),
            'navbar-dark navbar-info' => trans('lang.app_setting_sky_blue'),
            'navbar-dark navbar-success' => trans('lang.app_setting_green'),
            'navbar-dark navbar-indigo' => trans('lang.app_setting_indigo'),
            'navbar-dark navbar-purple' => trans('lang.app_setting_purple'),
            'navbar-dark navbar-pink' => trans('lang.app_setting_pink'),
            'navbar-dark navbar-navy' => trans('lang.app_setting_navy'),
            'navbar-light navbar-teal' => trans('lang.app_setting_teal'),
            'navbar-dark navbar-lightblue' => trans('lang.app_setting_lightblue'),
            'navbar-dark navbar-cyan' => trans('lang.app_setting_cyan'),
            'navbar-light navbar-orange' => trans('lang.app_setting_orange'),
            'navbar-light' => trans('lang.app_setting_transparent'),
        ];

        $logo_bg_clrs = [
            '' => trans('lang.app_setting_clear'),
            'text-light  navbar-primary' => trans('lang.app_setting_blue'),
            'text-light  navbar-gray' => trans('lang.app_setting_gray'),
            'text-light  navbar-dark' => trans('lang.app_setting_dark'),
            'text-dark  navbar-white' => trans('lang.app_setting_white'),
            'text-light  navbar-danger' => trans('lang.app_setting_red'),
            'text-dark  navbar-warning' => trans('lang.app_setting_yellow'),
            'text-light  navbar-info' => trans('lang.app_setting_sky_blue'),
            'text-light  navbar-success' => trans('lang.app_setting_green'),
            'text-light  navbar-indigo' => trans('lang.app_setting_indigo'),
            'text-light  navbar-purple' => trans('lang.app_setting_purple'),
            'text-light  navbar-pink' => trans('lang.app_setting_pink'),
            'text-light  navbar-navy' => trans('lang.app_setting_navy'),
            'text-dark  navbar-teal' => trans('lang.app_setting_teal'),
            'text-light  navbar-lightblue' => trans('lang.app_setting_lightblue'),
            'text-light  navbar-cyan' => trans('lang.app_setting_cyan'),
            'text-dark  navbar-orange' => trans('lang.app_setting_orange'),
            'text-dark navbar-light' => trans('lang.app_setting_light'),
        ];

        $image_path = setting('app_logo', '') != '' ? 'uploads/'. setting('app_logo') : '';
        $banner_img = setting('banner_img', '') != '' ? 'uploads/'. setting('banner_img') : '';

        return view('admins.settings.global-settings.index', compact('themes','logo_bg_clrs','navbar_colors','image_path', 'banner_img'));
    }

    public function update(Request $request, string $id)
    {
        $settings = $this->validate($request, [
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'instagram_link' => 'nullable|url',
            'whatsapp_link' => 'nullable|url',
            'twitter_link' => 'nullable|url',
            'facebook_link' => 'nullable|url',
            'theme_contrast' => 'required|in:light,dark',
            'theme_color' => 'required|in:primary,secondary,success,danger,warning,info,light,dark',
            'nav_color' => 'required|string',
            'logo_bg_color' => 'required|string',
            'fixed_header' => 'required|boolean',
            'fixed_footer' => 'required|boolean',
            'app_logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_img' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('app_logo')) {
            $path = $this->uploadImage($request->app_logo, 'uploads');
            $settings['app_logo'] = $path;
        }

        if ($request->hasFile('banner_img')) {
            $path = $this->uploadImage($request->banner_img, 'uploads');
            $settings['banner_img'] = $path;
        }

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.settings.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.app_setting_globals')]));
    }
}
