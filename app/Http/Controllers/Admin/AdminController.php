<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AdminController extends Controller
{
    //** DASHBOARD */
    public function dashboard(){
        return view('admins.dashboard');
    }
}
