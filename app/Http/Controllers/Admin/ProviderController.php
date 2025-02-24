<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index(){
        $services = Provider::paginate(10);
        return view('admins.services.index', compact('services'));
    }
}
