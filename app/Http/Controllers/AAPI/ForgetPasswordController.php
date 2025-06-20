<?php

namespace App\Http\Controllers\AAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ForgetPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => __('auth.email_required'),
            'email.email' => __('auth.email_invalid'),
            'email.exists' => __('auth.email_not_found'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json([
                'success' => true,
                'message' => __('auth.reset_link_sent'),
                'data' => []
            ], 200)
            : response()->json([
                'success' => false,
                'data' => [],
                'message' => __($status)
            ], 400);
    }
}
