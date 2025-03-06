<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class MailConfController extends Controller
{
    public function smtp()
    {
        return view('admins.settings.mails.smtp');
    }
    public function mailgun()
    {
        return view('admins.settings.mails.mailgun');
    }

    public function sparkpost()
    {
        return view('admins.settings.mails.sparkpost');
    }

    public function update_smtp(Request $request, string $id)
    {
        $smtp = $this->validate($request, [
            'enable_email_notifications' => 'required',
            'mail_driver' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_encryption' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'nullable',
            'mail_from_address' => 'required',
            'mail_from_name' => 'required',
        ]);

        foreach ($smtp as $key => $value) {
            if ($key == 'mail_password' && $value == null) {
                continue;
            }
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.mails.smtp')->with('success', __('lang.updated_successfully', ['operator' => __('lang.app_setting_smtp')]));
    }

    public function update_mailgun(Request $request, string $id)
    {
        $mailgun = $this->validate($request, [
            'mailgun_domain' => 'required',
            'mailgun_secret' => 'required',
            'mail_driver' => 'required',
        ]);

        foreach ($mailgun as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.mails.mailgun')->with('success', __('lang.updated_successfully', ['operator' => __('lang.app_setting_mailgun')]));
    }

    public function update_sparkpost(Request $request, string $id)
    {
        $sparkpost = $this->validate($request, [
            'sparkpost_options_endpoint' => 'required',
            'sparkpost_secret' => 'required',
            'mail_driver' => 'required',
        ]);

        foreach ($sparkpost as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.mails.sparkpost')->with('success', __('lang.updated_successfully', ['operator' => __('lang.app_setting_sparkpost')]));
    }
}
