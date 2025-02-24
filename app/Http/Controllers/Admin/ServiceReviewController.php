<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ServiceReviewDataTable;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceReview;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceReviewController extends Controller
{
    public function index(ServiceReviewDataTable $dataTable)
    {
        return $dataTable->render('admins.service-reviews.index');
    }

    public function create()
    {
        $services = Service::all();
        $users = User::all();
        $rates = [1, 2, 3, 4, 5];
        return view('admins.service-reviews.create', compact('services', 'users', 'rates'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'rate' => 'required',
            'review' => 'required',
        ]);

        ServiceReview::create($validatedData);

        return redirect()->route('admin.service-reviews.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.e_service_review')]));
    }


    public function edit($id)
    {
        $service_review = ServiceReview::findOrFail($id);
        $services = Service::all();
        $users = User::all();
        $rates = [1, 2, 3, 4, 5];
        return view('admins.service-reviews.edit', compact('services', 'users', 'rates', 'service_review'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'rate' => 'required',
            'review' => 'required',
        ]);

        ServiceReview::create($validatedData);

        return redirect()->route('admin.service-reviews.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.e_service_review')]));
    }

    public function destroy($id)
    {
        $service_review = ServiceReview::findOrFail($id);
        $service_review->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.e_service_review')])
        ], 200);
    }
}
