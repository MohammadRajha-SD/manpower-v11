<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\HelloMail;
use App\Mail\HelloMailArabic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class AuthUserController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|string|max:15',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
            'confirmation_code' => Str::random(25),
        ]);


        // Generate a custom confirmation URL
        $confirmationUrl = url('register/confirm/' . $user->confirmation_code);


        try {
            if ($request->lang == 'ar') {
                Mail::to($user->email)->send(new HelloMail($user, $confirmationUrl));
            } else {
                Mail::to($user->email)->send(new HelloMailArabic($user, $confirmationUrl));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send registeration email: ' . $e->getMessage());
        }

        // Log the user in after registration
        Auth::login($user);

        // Create a token for the logged-in user
        $token = $user->createToken('main')->plainTextToken;

        return response()->json([
            'message' => 'User registered and logged in successfully!',
            'user' => $user,
            'user_type' => 'user',
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            $token = $user->createToken('main')->plainTextToken;

            return response()->json([
                'message' => 'User retrieved successfully',
                'user' => $user,
                'token' => $token,
            ], 200);
        }

        return response()->json(__('auth.failed'), 401);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logout successfully',
        ], 201);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user_type' => 'user',
            'data' => [
                'user_type' => 'user',
                'id' => $user->id,
                'address' => $user->address ?? null,
                'addresses' => $user->addresses ?? [],
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'is_admin' => $user->is_admin,
                'email_verified_at' => $user->email_verified_at,
                'is_verified' => $user->confirmation_code == null,
                'image_path' => user_image($user),
                'bookings' => $user->bookings?->map(function ($b) {
                    return [
                        'id' => $b->id,
                        'address'=> $b->address,
                        'service_id' => $b->service_id,
                        'booking_status' => $b->booking_status->status,
                        'payment_status' => $b->payment?->payment_status?->status,
                        'payment_link' => $b->payment?->stripe_payment_link,
                    ];
                }),
            ],
        ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'The current password is required.',
            'password.required' => 'The new password is required.',
            'password.min' => 'The new password must be at least 8 characters.',
            'password.confirmed' => 'The new password and confirmation do not match.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = $request->user();


        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => "The old password is incorrect."
            ], 400);
        }

        if ($request->current_password === $request->password) {
            return response()->json([
                'status' => 'error',
                'message' => "The new password must be different from the old password."
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Password updated successfully."
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email address.',
            'email.exists' => 'No account found with this email.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 400);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
            ? response()->json([
                'status' => 'success',
                'message' => 'Password reset link sent. Check your email.'
            ], 200)
            : response()->json([
                'status' => 'error',
                'message' => __($status)
            ], 400);
    }

    public function resetPasswordOld(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email not found',
                ], 404);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Password updated successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server Error',
                $e->getMessage(),
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:6|confirmed',
                'token' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], 400);
            }

            // Use Laravel's built-in Password Broker to verify the token
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->save();
                }
            );

            if ($status == Password::PASSWORD_RESET) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Password has been reset successfully.',
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => __($status),
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server Error',
                'exception' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateAddress(Request $request)
    {
        $request->validate([
            'address' => 'nullable|string|max:255',
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user->address = $request->address;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => "Address updated successfully.",
            'user' => $user,
        ]);
    }
}
