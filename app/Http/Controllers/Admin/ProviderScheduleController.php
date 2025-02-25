<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProviderScheduleDataTable;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\ProviderSchedule;
use Illuminate\Http\Request;

class ProviderScheduleController extends Controller
{
    public function index(ProviderScheduleDataTable $dataTable)
    {
        return $dataTable->render('admins.providers.provider-schedules.index');
    }
    public function create()
    {
        // todo:
        $providers = Provider::all();
        return view('admins.providers.provider-schedules.create', compact('providers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'day' => 'required|string|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'start_at' => 'required',
            'end_at' => 'required|after:start_at',
            'data' => 'nullable',
            'provider_id' => 'required|exists:providers,id',
        ]);

        // Create the record
        ProviderSchedule::create($validatedData);

        return redirect()->route('admin.provider-schedules.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.availability_hour')]));
    }

    public function edit($id)
    {
        $providers = Provider::all();
        $schedule = ProviderSchedule::findOrFail($id);
        return view('admins.providers.provider-schedules.edit', compact('providers', 'schedule'));
    }

    public function update(Request $request, ProviderSchedule $schedule)
    {
        $validatedData = $request->validate([
            'day' => 'required|string|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'start_at' => 'required',
            'end_at' => 'required|after:start_at',
            'data' => 'nullable',
            'provider_id' => 'required|exists:providers,id',
        ]);

        // Update the record
        $schedule->update($validatedData);

        return redirect()->route('admin.provider-schedules.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.availability_hour')]));
    }


    public function destroy($id)
    {
        $schedule = ProviderSchedule::findOrFail($id);
        $schedule->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.availability_hour')])
        ], 200);
    }
}
