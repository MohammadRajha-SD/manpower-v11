<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\CurrencyDataTable;
use App\Models\Currency;

class CurrencyController extends Controller
{
    public function index(CurrencyDataTable $dataTable)
    {
        return $dataTable->render('admins.settings.currency.index');
    }

    public function create()
    {
        return view('admins.settings.currency.create');
    }

    public function edit($id)
    {
        $currency = Currency::findOrFail($id);
        return view('admins.settings.currency.edit', compact('currency'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:currencies,name',
            'symbol' => 'required|string|max:10',
            'code' => 'required|string|max:10|unique:currencies,code',
            'decimal_digits' => 'required|integer|min:0|max:10',
            'rounding' => 'required|integer|min:0|max:100',
        ]);

        Currency::create($request->all());

        return redirect()->route('admin.currencies.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.currency')]));
    }

    public function update(Request $request, $id)
    {
        $currency = Currency::findOrFail($id);

        // Validation
        $request->validate([
            'name' => 'required|string|max:255|unique:currencies,name,' . $currency->id,
            'symbol' => 'required|string|max:10',
            'code' => 'required|string|max:10|unique:currencies,code,' . $currency->id,
            'decimal_digits' => 'required|integer|min:0|max:10',
            'rounding' => 'required|integer|min:0|max:100',
        ]);

        // Update the currency
        $currency->update($request->all());
        return redirect()->route('admin.currencies.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.currency')]));
    }

    public function destroy($id)
    {
        $currency = Currency::findOrFail($id);
        $currency->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.currency')])
        ], 200);
    }
}
