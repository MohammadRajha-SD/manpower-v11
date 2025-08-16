<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\MostPopularDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ImageHandler;
use App\Models\Category;
use App\Models\MostPopular;

class MostPopularController extends Controller
{
    use ImageHandler;

    public function index(MostPopularDataTable $dataTable)
    {
        return $dataTable->render('admins.most-populars.index');
    }

    public function create()
    {
        $categories = Category::where('is_end_sub_category', 1)->get();

        return view('admins.most-populars.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $mp = MostPopular::create([
            'name' => $request->name,
            'desc' => $request->description,
            'category_id' => $request->category_id,
        ]);

        if ($request->hasFile('image')) {
            $path = $this->uploadImage($request->image, 'uploads');

            $mp->image()->create(['path' => $path]);
        }

        return redirect()->route('admin.most-populars.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.most_popular')]));
    }

    public function edit($id)
    {
        $most_popular = MostPopular::with('image', 'category')->findOrFail($id);
        $categories = Category::where('is_end_sub_category', 1)->get();

        return view('admins.most-populars.edit', compact('most_popular', 'categories'));
    }

    public function update(Request $request, MostPopular $most_popular)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'category_id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $most_popular->update([
            'name' => $request->name,
            'desc' => $request->description,
            'category_id' => $request->category_id,
        ]);

        if ($request->hasFile('image')) {
            $path = $this->uploadImage($request->image, 'uploads');

            $most_popular->image()->delete();
            $most_popular->image()->create(['path' => $path]);
        }

        return redirect()->route('admin.most-populars.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.most_popular')]));
    }

    public function destroy($id)
    {
        $mp = MostPopular::findOrFail($id);

        if ($mp->image()->exists()) {
            $mp->image()->delete();
            $mp->deleteImage($mp->image->path);
        }

        $mp->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.most_popular')])
        ], 200);
    }
}
