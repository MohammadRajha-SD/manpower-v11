<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MultiLoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'The email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'The password is required.',
        ]);


        $user = User::where('email', $request->email)->first();
        $provider = Provider::where('email', $request->email)->first();

        if ($user && !$provider) {
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();

                $token = $user->createToken('main')->plainTextToken;

                if ($user->is_admin === 1) {
                    return response()->json([
                        'message' => 'Admin retrieved successfully',
                        'data' => $user,
                        'token' => $token,
                        'is_admin' => 1,
                        'user_type' => 'admin',
                        'to' => env('BACKEND_URL', 'http://127.0.0.1:8000'),
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'User retrieved successfully',
                        'data' => $user,
                        'token' => $token,
                        'is_admin' => 0,
                        'user_type' => 'user',
                        'to' => env('FRONTEND_URL', 'http://localhost:3000'),
                    ], 200);
                }
            }
        } else if ($provider && !$user) {
            if (Auth::guard('provider')->attempt($request->only('email', 'password'))) {
                $provider = Auth::guard('provider')->user();
                $token = $provider->createToken('main')->plainTextToken;

                return response()->json([
                    'message' => 'Provider retrieved successfully',
                    'data' => $provider,
                    'token' => $token,
                    'user_type' => 'provider',
                    'to' => env('FRONTEND_URL', 'http://localhost:3000'),
                ], 200);
            }
        } else if ($provider && $user) {
            return response()->json([
                'message' => 'something went wrong...',
                'data' => [],
                'token' => null,
                'user_type' => null,
                'to' => null,
            ], 200);
        }

        return response()->json([
            'message' => __('auth.failed'),
            'data' => [],
            'token' => null,
            'user_type' => null,
            'to' => null,
        ], 401);
    }
}
