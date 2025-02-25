<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\FaqCatgoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqCategoryController extends Controller
{
    public function index(FaqCatgoryDataTable $dataTable)
    {
        return $dataTable->render('admins.faq-categories.index');
    }

    public function create()
    {
        return view('admins.faq-categories.create');
    }

    public function store(Request $request)
    {
        $data = $this->validate(
            $request,
            [
                'name' => 'required|string|max:255'
            ]
        );

        FaqCategory::create($data);

        return redirect()->route('admin.faq-categories.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.faq_category')]));
    }

    public function edit($id)
    {
        $category = FaqCategory::findOrFail($id);
        return view('admins.faq-categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $data = $this->validate(
            $request,
            [
                'name' => 'required|string|max:255'
            ]
        );

        $category = FaqCategory::findOrFail($id);
        $category->update($data);

        return redirect()->route('admin.faq-categories.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.faq_category')]));
    }

    public function destroy($id)
    {
        $category = FaqCategory::findOrFail($id);
        
        if ($category->faqs()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => __('lang.cannot_delete_has_children', ['operator' => __('lang.faq')]),
            ]);
        }
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.faq_category')])
        ], 200);
    }
}
