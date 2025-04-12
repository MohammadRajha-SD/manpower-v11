<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ImageHandler;

    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('admins.categories.index');
    }

    public function create()
    {
        $parentCategory = Category::all();
        return view('admins.categories.create', compact('parentCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'color' => 'required|string',
            'order' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'desc' => $request->description,
            'color' => $request->color,
            'order' => $request->order,
            'featured' => $request->featured ?? false,
            'parent_id' => $request->parent_id,
        ]);

        // Upload images and associate with category
        if ($request->hasFile('images')) {
            $paths = $this->uploadMultiImage($request->images, 'uploads');

            foreach ($paths as $path) {
                $category->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.categories.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.category')]));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $parentCategory = Category::where('id', '!=', $category->id)->get();

        return view('admins.categories.edit', compact('category', 'parentCategory'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'color' => 'required|string',
            'order' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category->update([
            'name' => $request->name,
            'desc' => $request->description,
            'color' => $request->color,
            'order' => $request->order,
            'featured' => $request->featured ?? false,
            'parent_id' => $request->parent_id,
        ]);

        if ($request->hasFile('images')) {
            $paths = $this->uploadMultiImage($request->images, 'uploads');

            foreach ($paths as $path) {
                $category->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.categories.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.category')]));
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->children()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => __('lang.cannot_delete_has_children' , ['operator' => __('lang.category')]),
            ]);
        }

        if ($category->services()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => __('lang.cannot_delete_has_children' , ['operator' => __('lang.e_service')]),
            ]);
        }

        // Check if provider has images before deleting
        if ($category->images()->exists()) {
            // Delete related images
            foreach ($category->images as $image) {
                $image->delete();
                $category->deleteImage($image->path);
            }
        }

        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.category')])
        ], 200);
    }
}
