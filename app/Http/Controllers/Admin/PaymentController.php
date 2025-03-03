<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PaymentDataTable;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(PaymentDataTable $dataTable)
    {
        return $dataTable->render('admins.payments.index');
    }

    public function byMonth()
    {
        $payments = Payment::orderBy("created_at", 'asc')->get();

        if ($payments->isEmpty()) {
            return response()->json([
                'data' => [],
                'labels' =>[],
                'message' => 'No payments found.',
            ], 200);
        }

        $payments = $payments->map(function ($row) {
            $row['month'] = $row->created_at->format('M');
            return $row;
        })->groupBy('month')->map(function ($row) {
            return number_format((float) $row->sum('amount'), setting('default_currency_decimal_digits', 2), '.', '');
        });

        return response()->json([
            'status' => 'success',
            'data' => array_values($payments->toArray()),
            'labels' => array_keys($payments->toArray()),
            'message' => 'Payment retrieved successfully',
        ], 200);
    }
}
