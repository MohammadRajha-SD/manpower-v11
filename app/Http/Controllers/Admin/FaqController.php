<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\FaqDataTable;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(FaqDataTable $dataTable)
    {
        return $dataTable->render('admins.faqs.index');
    }

    public function create()
    {
        $categories = FaqCategory::all();

        return view('admins.faqs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'question' => 'required',
            'answer' => 'required',
            'faq_category_id' => 'required|exists:faq_categories,id'
        ]);


        Faq::create($data);

        return redirect()->route('admin.faqs.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.faq')]));
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        $categories = FaqCategory::all();

        return view('admins.faqs.edit', data: compact('categories', 'faq'));
    }

    public function update(Request $request, $id)
    {
        $data = $this->validate($request, [
            'question' => 'required',
            'answer' => 'required',
            'faq_category_id' => 'required|exists:faq_categories,id'
        ]);

        Faq::findOrFail($id)->update($data);

        return redirect()->route('admin.faqs.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.faq')]));
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.faq')])
        ], 200);
    }

}
