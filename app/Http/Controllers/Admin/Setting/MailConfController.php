<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class MailConfController extends Controller
{
    public function index()
    {
        return view('admins.settings.mails.index');
    }

    public function update(Request $request, string $id)
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
            if($key == 'mail_password' && $value == null){
                continue;
            }
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.mails.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.app_setting_smtp')]));
    }

}
