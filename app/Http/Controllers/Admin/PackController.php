<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PackDataTable;
use App\Http\Controllers\Controller;
use App\Models\Pack;
use Illuminate\Http\Request;

class PackController extends Controller
{
    public function index(PackDataTable $dataTable)
    {
        return $dataTable->render('admins.packs.index');
    }

    public function create()
    {
        return view('admins.packs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stripe_plan_id' => 'required|string',
            'type' => 'required', 
            'price' => 'required|integer|min:0',
            'text' => 'required',
            'short_description' => 'required|string',
            'description' => 'required|string', 
            'number_of_months' => 'required|integer|min:1',
            'number_of_ads' => 'required|integer|min:0',
            'number_of_subcategories' => 'required|integer|min:0',
            'not_in_featured_services' => 'boolean',
            'not_on_image_slider' => 'boolean',
        ]);


        $pack = new Pack($validated);
        $pack->save();

        return redirect()->route('admin.packs.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.pack')]));
    }

    public function edit($id)
    {
        $pack = Pack::findOrFail($id);
        return view('admins.packs.edit', compact('pack'));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'stripe_plan_id' => 'required|string',
            'type' => 'required', 
            'price' => 'required|integer|min:0',
            'text' => 'required',
            'short_description' => 'required|string',
            'description' => 'required|string', 
            'number_of_months' => 'required|integer|min:1',
            'number_of_ads' => 'required|integer|min:0',
            'number_of_subcategories' => 'required|integer|min:0',
            'not_in_featured_services' => 'boolean',
            'not_on_image_slider' => 'boolean',
        ]);

        $pack = Pack::findOrFail($id);
        $pack->update($validated);

        return redirect()->route('admin.packs.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.pack')]));
    }

    public function destroy($id)
    {
        $pack = Pack::findOrFail($id);
        
        // TODO:
        // if ($pack->faqs()->exists()) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => __('lang.cannot_delete_has_children', ['operator' => __('lang.faq')]),
        //     ]);
        // }

        $pack->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.pack')])
        ], 200);
    }

}
