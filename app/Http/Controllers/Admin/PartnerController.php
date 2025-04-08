<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PartnerDataTable;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    use ImageHandler;

    public function index(PartnerDataTable $dataTable)
    {
        return $dataTable->render('admins.partners.index');
    }

    public function create()
    {
        return view('admins.partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'enabled' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $partner = Partner::create([
            'title' => $request->title,
            'desc' => $request->description,
            'enabled' => $request->enabled,
        ]);

        // Upload images and associate with category
        if ($request->hasFile('image')) {
            $path = $this->uploadImage($request->image, 'uploads');

            $partner->image()->create(['path' => $path]);
        }

        return redirect()->route('admin.partners.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.partner')]));
    }

    public function edit($id)
    {
        $partner = Partner::findOrFail($id);

        return view('admins.partners.edit', compact('partner'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'enabled' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $partner = Partner::findOrFail($id);
        $partner->update([
            'title' => $request->title,
            'desc' => $request->description,
            'enabled' => $request->enabled,
        ]);

        // Upload images and associate with category
        if ($request->hasFile('image')) {
            $path = $this->uploadImage($request->image, 'uploads');

            if ($partner->image()->exists()) {
                $imagePath = $partner->image->path;
                $this->deleteImage($imagePath);
                $partner->image()->delete();
            }

            $partner->image()->create(['path' => $path]);
        }

        return redirect()->route('admin.partners.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.partner')]));
    }

    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);

        // Check if partner has image before deleting
        if ($partner->image()->exists()) {
            $imagePath = $partner->image->path;

            $partner->image()->delete();
            $partner->deleteImage($imagePath);
        }

        $partner->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.partner')])
        ], 200);
    }
}
