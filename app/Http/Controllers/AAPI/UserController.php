<?php

namespace App\Http\Controllers\AAPI;

use App\Http\Controllers\Controller;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use ImageHandler;

    public function user(Request $request)
    {
        // $user = $request->user(); 
        $user = User::where('device_token', $request->api_token)?->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => __('auth.unauthenticated'),
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => __('auth.login_success'),
            'data' => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "phone" => $user->phone_number,
            ],
        ]);
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;

        // if ($request->hasFile('avatar')) {
        //     $avatar = $request->file('avatar');
        //     $this->uploadImage($avatar, 'uploads');
        //     $filename = time() . '.' . $avatar->getClientOriginalExtension();
        //     $path = $avatar->storeAs('public/avatars', $filename);
        //     $user->avatar = str_replace('public/', 'storage/', $path); // so it can be accessed via URL
        // }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "phone" => $user->phone_number,
                // "avatar" => $user->avatar ? asset($user->avatar) : null,
            ],
        ]);
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'password' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully',
            'data' => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "phone" => $user->phone_number,
            ],
        ]);
    }
}
