<?php

namespace App\Http\Controllers\Admin\Setting;

use App\DataTables\SlideDataTable;
use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    public function index(SlideDataTable $dataTable)
    {
        return $dataTable->render('admins.settings.slides.index');
    }

    public function create()
    {
        return view('admins.settings.slides.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'text' => 'required',
            'description' => 'required',
            'status' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slide = Slide::create([
            'text' => $request->text,
            'desc' => $request->description,
            'status' => $request->status,
        ]);

        // Upload image and associate with slide
        if ($request->hasFile('image')) {
            $path = $slide->uploadImage($request->image, 'uploads');
            $slide->image()->create(['path' => $path]);
        }

        return redirect()->route('admin.slides.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.slide')]));
    }

    public function edit($id)
    {
        $slide = Slide::findOrFail($id);

        return view('admins.settings.slides.edit', data: compact('slide'));
    }

    public function update(Request $request, Slide $slide)
    {
        $this->validate($request, [
            'text' => 'required',
            'description' => 'required',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slide->update([
            'status' => $request->status,
            'text' => $request->text,
            'desc' => $request->description,
        ]);

        // Upload image and associate with slide
        if ($request->hasFile('image')) {
            $path = $slide->uploadImage($request->image, 'uploads');

            $slide->image()->delete();
            $slide->image()->create(['path' => $path]);
        }

        return redirect()->route('admin.slides.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.slide')]));
    }

    public function destroy($id)
    {
        $slide = Slide::findOrFail($id);
        $slide->deleteImage('uploads/'.$slide->image->path);
        $slide->image()->delete();
        $slide->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.slide')])
        ], 200);
    }
}
