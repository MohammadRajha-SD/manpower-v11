<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Earning;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    //** DASHBOARD */
    public function dashboard()
    {
        $booking_counted = Booking::count();
        $providers_counted = Provider::count();
        $members_counted = User::count();
        $earning = Earning::all()->sum('total_earning');
        
        return view('admins.dashboard', compact('booking_counted', 'providers_counted','members_counted','earning'));
    }
}
