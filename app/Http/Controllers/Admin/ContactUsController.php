<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ContactUsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function index(ContactUsDataTable $dataTable)
    {
        return $dataTable->render('admins.contact-us.index');
    }
}
