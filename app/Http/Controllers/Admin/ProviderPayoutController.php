<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProviderPayoutDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProviderPayoutController extends Controller
{
    public function index(ProviderPayoutDataTable $dataTable)
    {
        return $dataTable->render('admins.provider-payouts.index');
    }
}
