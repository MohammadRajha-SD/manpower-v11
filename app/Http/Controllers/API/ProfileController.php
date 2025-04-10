<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function store(Request $request)
    {
        if (Auth::guard('provider')->check()) {
            $guard = 'provider';
            $authUser = Auth::guard('provider')->user();
        } elseif (Auth::guard()->check()) {
            $guard = 'user';
            $authUser = Auth::guard()->user();
        } else {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique($guard . 's')->ignore($authUser->id),
            ],
            'phone_number' => [
                'nullable',
                'string',
                Rule::unique($guard . 's')->ignore($authUser->id),
            ],
            'user_type' => ['required', Rule::in(['provider', 'user'])],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        $data = $validator->validated();
        unset($data['user_type']);
        
        // Update profile
        $authUser->update($data);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => $authUser,
        ]);
    }
}
