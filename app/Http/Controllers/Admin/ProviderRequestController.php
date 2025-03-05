<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProviderRequestDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProviderRequestController extends Controller
{
    public function index(ProviderRequestDataTable $dataTable)
    {
        return $dataTable->render('admins.providers.provider-requests.index');
    }

}
