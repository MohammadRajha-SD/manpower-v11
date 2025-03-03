<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProviderTypeDataTable;
use App\Http\Controllers\Controller;
use App\Models\ProviderType;
use Illuminate\Http\Request;

class ProviderTypeController extends Controller
{

    public function index(ProviderTypeDataTable $dataTable)
    {
        return $dataTable->render('admins.providers.provider-types.index');
    }

    public function create()
    {
        return view('admins.providers.provider-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'commission' => 'required',
            'disabled' => 'required|integer',
        ]);

        ProviderType::create([
            'name' => $request->name,
            'commission' => $request->commission,
            'disabled' => $request->disabled,
        ]);

        return redirect()->route('admin.provider-types.index')->with('success', __('lang.saved_successfully',['operator' => __('lang.e_provider_type')]));
    }

    public function edit(string $id)
    {
        $provider_type = ProviderType::findOrFail($id);

        return view('admins.providers.provider-types.edit', compact('provider_type'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'commission' => 'required',
            'disabled' => 'required|integer',
        ]);

        $provider_type = ProviderType::findOrFail($id);

        $provider_type->update([
            'name' => $request->name,
            'commission' => $request->commission,
            'disabled' => $request->disabled,
        ]);

        return redirect()->route('admin.provider-types.index')->with('success', __('lang.updated_successfully',['operator' => __('lang.e_provider_type')]));
    }

    public function destroy($id)
    {
        $provider_type = ProviderType::findOrFail($id);

        if ($provider_type->children()->exists()) {
            return redirect()->back()->with('error', __('lang.cannot_delete_has_children', ['operator'=> __('lang.e_provider_type')]));
        }

        $provider_type->delete();

        return redirect()->back()->with('success', __('lang.deleted_successfully',['operator' => __('lang.e_provider_type')]));
    }
}
