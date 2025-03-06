<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Setting;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        return view('admins.settings.payment.index', compact('currencies'));
    }

    public function update(Request $request, string $id)
    {
        $payment = $this->validate($request, [
            'default_tax' => 'required',
            'default_currency_id' => 'required|exists:currencies,id',
            'currency_right' => 'required',
        ]);

        $currency = Currency::findOrFail($payment['default_currency_id']);

        Setting::updateOrCreate(['key' => 'default_currency_id'], ['value' => $currency->id]);
        Setting::updateOrCreate(['key' => 'default_currency'], ['value' => $currency->symbol]);
        Setting::updateOrCreate(['key' => 'default_currency_code'], ['value' => $currency->code]);
        Setting::updateOrCreate(['key' => 'default_currency_decimal_digits'], ['value' => $currency->decimal_digits]);
        Setting::updateOrCreate(['key' => 'default_currency_rounding'], ['value' => $currency->rounding]);
        
        Setting::updateOrCreate(['key' => 'currency_right'], ['value' => $payment['currency_right']]);
        Setting::updateOrCreate(['key' => 'default_tax'], ['value' => $payment['default_tax']]);
        
        return redirect()->route('admin.setting-payment.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.app_setting_payment')]));
    }
}
