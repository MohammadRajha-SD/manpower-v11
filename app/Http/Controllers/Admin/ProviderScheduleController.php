<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProviderScheduleDataTable;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\ProviderSchedule;
use Illuminate\Http\Request;
use DateTimeZone;

class ProviderScheduleController extends Controller
{
    public function index(ProviderScheduleDataTable $dataTable)
    {
        return $dataTable->render('admins.providers.provider-schedules.index');
    }
    public function create()
    {
        return view('admins.providers.provider-schedules.create');
    }


    public function edit($id)
    {
        return view('admins.providers.provider-schedules.edit', compact('id'));
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
