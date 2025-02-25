<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TaxDataTable;
use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index(TaxDataTable $dataTable)
    {
        return $dataTable->render('admins.providers.taxes.index');
    }
    public function create()
    {
        return view('admins.providers.taxes.create');
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'value' => 'required',
            'type' => 'required|in:percent,fixed',
        ]);

        Tax::create($data);

        return redirect()->route('admin.taxes.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.tax')]));
    }

    public function edit($id)
    {
        $tax = Tax::findOrFail($id);

        return view('admins.providers.taxes.edit', compact('tax'));
    }

    public function update(Request $request, Tax $tax)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'value' => 'required',
            'type' => 'required|in:percent,fixed',
        ]);

        $tax->update($data);

        return redirect()->route('admin.taxes.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.tax')]));
    }

    public function destroy($id)
    {
        $tax = Tax::findOrFail($id);

        // Check if the tax is linked to any providers
        if ($tax->providers()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => __('lang.cannot_delete_has_children' ,['operator' => __('lang.tax')]),
            ]);
        }

        $tax->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.tax')])
        ], 200);
    }
}
