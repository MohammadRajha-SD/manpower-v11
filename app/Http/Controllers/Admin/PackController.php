<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pack;
use Illuminate\Http\Request;

class PackController extends Controller
{
    public function index(){
        $packs = Pack::paginate(10);
        return view('admins.packs.index', compact('packs'));
    }
}
