<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AddressDataTable;
use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index(AddressDataTable $dataTable)
    {
        return $dataTable->render('admins.providers.addresses.index');
    }
    public function create()
    {
        return view('admins.providers.addresses.create');
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'address' => 'required',
            'description' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'default' => 'required',
        ]);

        $data['user_id'] = Auth::id();
        Address::create($data);

        return redirect()->route('admin.addresses.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.address')]));
    }

    public function edit($id)
    {
        $address = Address::findOrFail($id);

        return view('admins.providers.addresses.edit', compact('address'));
    }

    public function update(Request $request)
    {
        $data = $this->validate($request, [
            'address' => 'required',
            'description' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'default' => 'required',
        ]);

        $data['user_id'] = Auth::id();
        Address::create($data);

        return redirect()->route('admin.addresses.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.address')]));
    }

    public function destroy($id)
    {
        $address = Address::findOrFail($id);

        // Check if the address is linked to any providers
        if ($address->providers()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => __('lang.cannot_delete_has_children' ,['operator' => __('lang.provider')]),
            ]);
        }

        $address->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.address')])
        ], 200);
    }
}
