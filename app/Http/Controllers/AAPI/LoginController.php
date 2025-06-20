<?php

namespace App\Http\Controllers\AAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Mail\HelloMail;
use App\Mail\HelloMailArabic;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
            ], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('mobile_app_token')->plainTextToken;

        $user->update([
            'device_token' => $token ?? '',
        ]);

        // Return data
        return response()->json([
            'success' => true,
            'message' => 'Login success',
            'data' => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "api_token" => $token,
                "phone" => $user->phone_number,
                "avatar" => null,
            ],
        ]);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|string|max:15',
            'password' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $username = $this->generateUniqueUsername();

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'username' => $username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
            'confirmation_code' => Str::random(25),
        ]);


        // Generate a custom confirmation URL
        // $confirmationUrl = url('register/confirm/' . $user->confirmation_code);

        // Mail::to($user->email)->send(new HelloMail($user, $confirmationUrl));
        // Mail::to($user->email)->send(new HelloMailArabic($user, $confirmationUrl));

        // Log the user in after registration
        Auth::login($user);

        // Create a token for the logged-in user
        $token = $user->createToken('main')->plainTextToken;
        
        $user->update([
            'device_token' => $token ?? '',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered and logged in successfully!',
            'data' => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "api_token" => $token,
                "phone" => $user->phone_number,
                "avatar" => null,
            ],
        ], 201);
    }




    function generateUniqueUsername()
    {
        do {
            $username = 'usr' . Str::random(6);
        } while (User::where('username', $username)->exists());

        return $username;
    }

}
