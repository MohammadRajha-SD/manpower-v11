<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TestimonialDataTable;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(TestimonialDataTable $dataTable)
    {
        return $dataTable->render('admins.testimonials.index');
    }

    public function create()
    {
        return view('admins.testimonials.create');
    }

    public function store(Request $request)
    {
        $data = $this->validate(
            $request,
            [
                'name' => 'required|max:255',
                'description' => 'nullable',
                'stars' => 'required:min:1',
            ]
        );

        $ts = new Testimonial();
        $ts->name = $request->name;
        $ts->description = $request->description ?? '';
        $ts->stars = $request->stars;
        $ts->save();

        return redirect()->route('admin.testimonials.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.testimonial')]));
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        return view('admins.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, $id)
    {
        $data = $this->validate(
            $request,
            [
                'name' => 'required|max:255',
                'description' => 'nullable',
                'stars' => 'required:min:1',
            ]
        );

        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.testimonial')]));
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.testimonial')])
        ], 200);
    }
}
