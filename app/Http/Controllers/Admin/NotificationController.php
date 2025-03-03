<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\NotificationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(NotificationDataTable $dataTable)
    {
        return $dataTable->render('admins.notifications.index');
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.notification')])
        ], 200);
    }
}
