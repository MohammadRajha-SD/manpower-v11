<?php

use App\Models\Setting;
use Illuminate\Support\Str;

/** Set Sidebar Item Active */
if (!function_exists('setActive')) {
    function setActive(array $route)
    {
        if (is_array($route)) {
            foreach ($route as $r) {
                if (request()->routeIs($r)) {
                    return 'active';
                }
            }
        }
    }
}

/** TODO: when create database && settings table */
if (!function_exists('setting')) {

    function setting($key = null, $default = null)
    {
        $setting = Setting::firstWhere('key', $key);

        if ($setting && $setting->value != '') {
            return $setting->value;
        }

        return $default;
    }
}

if (!function_exists('appLogo')) {
    // todo: path of image like uploads
    function appLogo()
    {
        $logo = setting('app_logo', null);
        return $logo == null ? asset('images/logo_default.png') : asset($logo);
    }
}

if (!function_exists('user_image')) {
    function user_image($user)
    {
        if ($user && $user->image) {
            // todo:
            return asset('storage/' . $user->image->path);
        }

        return asset('images/avatar_default.png');
    }
}

if (!function_exists('allLanguages')) {
    function allLanguages()
    {
        // $langs = ['ar', 'ku', 'fa', 'ur', 'he', 'ha', 'ks'];
        $langs = ['ar', 'en', 'fr'];
        return $langs;
    }
}

if (!function_exists('avaiableLanguages')) {
    function avaiableLanguages()
    {
        $langs = allLanguages() ?? [];

        return array_map(function ($lang) {
            return [
                'code' => $lang,
                'name' => trans('lang.app_setting_' . $lang),
            ];
        }, $langs);
    }
}



if (!function_exists('desc_limit')) {
    function desc_limit($desc, $limit = 40)
    {
        if ($desc == null) {
            return null;
        }
        $text = strip_tags($desc);

        return $limit > 0 ? Str::limit($text, $limit) : $text;
    }
}

if (!function_exists('image_item')) {
    function image_item($item)
    {
        if ($item && count($item->images) > 0) {
            $path = $item->images->first()->path;
            return "<img class='rounded' style='height:50px;width:50px;' src='" . asset($path) . "' alt=''>";
        } else {
            return "<img class='rounded' style='height:50px;width:50px;' src='" . asset('images/image_default.png') . "' alt='default image'>";
        }
    }
}

if (!function_exists('toJsonLang')) {
    function toJsonLang($value)
    {
        $lang = app()->getLocale() ?? 'en';

        return json_encode([$lang => $value], JSON_UNESCAPED_UNICODE);
    }
}
