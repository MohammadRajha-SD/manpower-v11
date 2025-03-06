<?php

use App\Models\Setting;
use Illuminate\Support\Str;

/** Set Sidebar Item Active */
if (!function_exists('setActive')) {
    function setActive(array $route, $returned = 'active')
    {
        if (is_array($route)) {
            foreach ($route as $r) {
                if (request()->routeIs($r)) {
                    return $returned;
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
    function appLogo()
    {
        $logo = setting('app_logo', null);
        return $logo == null ? asset('images/logo_default.png') : asset('uploads/' . $logo);
    }
}

if (!function_exists('user_image')) {
    function user_image($user)
    {
        if ($user && $user->image) {
            return asset('uploads/' . $user->image->path);
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
    function image_item($item, $w = '75px', $h = '75px', $s = '', $c = 'rounded')
    {
        if ($item && $item->image) {
            $path = $item->image->path;
            return "<img class='$c' style='height:$w;width:$h;$s' src='" . asset('uploads/' . $path) . "' alt=''>";
        } else {
            return "<img class='rounded' style='height:50px;width:50px;' src='" . asset('images/image_default.png') . "' alt='default image'>";
        }
        // if ($item && count($item->images) > 0) {
        //     $path = $item->images->first()->path;
        //     return "<img class='rounded' style='height:50px;width:50px;' src='" . asset($path) . "' alt=''>";
        // }elseif ($item && $item->image()->exists()) {
        //     $path = $item->image->path;
        //     return "<img class='rounded' style='height:50px;width:50px;' src='" . asset($path) . "' alt=''>";
        // } else {
        //     return "<img class='rounded' style='height:50px;width:50px;' src='" . asset('images/image_default.png') . "' alt='default image'>";
        // }
    }
}


if (!function_exists('isActive')) {

    function isActive($name, $color1 = "danger", $color2 = "success", $returned1 = null, $returned2 = null)
    {
        $returned1 = $returned1 ?? __('lang.yes'); // Default value if not provided
        $returned2 = $returned2 ?? __('lang.no'); // Default value if not provided

        if ($name) {
            return "<span class='badge badge-" . $color1 . "'>" . $returned1 . "</span>";
        } else {
            return "<span class='badge badge-" . $color2 . "'>" . $returned2 . "</span>";
        }
    }
}

if (!function_exists('isFeatured')) {
    function isFeatured($name, $color = "info", $returned = 'featured')
    {
        if ($name) {
            return "<br/><span class='badge badge-" . $color . "'>" . $returned . "</span>";
        }
        return '';
    }
}

if (!function_exists('getDays')) {
    function getDays()
    {
        return ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    }
}

if (!function_exists('getActiveMailDriver')) {

    function getActiveMailDriver($driver = 'smtp')
    {
        if (setting('mail_driver') === $driver) {
            return '<span class="badge ml-2 badge-success">' . __('lang.active') . '</span>';
        }
        return null;
    }
}