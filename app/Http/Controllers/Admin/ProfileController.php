<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(){
        $user = auth()->user();
        return view('admins.settings.profile.index', compact('user'));
    }

    public function update(Request $request){
        $user = auth()->user();
        $request->validate([
            "name" => 'required|string|max:255',
            "email" => 'required|unique:users,email,' . $user->id . ',id',
            "phone_number" => 'required',
        ]);
    
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
    
        if ($request->hasFile('image')) {
            $path = $user->uploadImage($request->image, 'uploads');
    
            if ($user->image) {
                $user->image()->delete();
            }
    
            $user->image()->create(['path' => $path]);
        }
    
        $user->save();
    
        return redirect()->route('admin.dashboard')->with(__('lang.deleted_successfully', ['operator' => trans('lang.user_avatar')]));
    }
}
