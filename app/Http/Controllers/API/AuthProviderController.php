<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\HelloMail;
use App\Mail\HelloMailArabic;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthProviderController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:providers,email',
            'phone_number' => 'required|unique:providers,phone_number',
            'password' => 'required|string|min:6',
            'category_id' => 'nullable|integer|exists:categories,id',
            'provider_type_id' => 'nullable|exists:provider_types,id',
            'language' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $provider = Provider::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
            'confirmation_code' => Str::random(25),
            'category_id' => $request->category_id,
            'provider_type_id' => $request->provider_type_id,
            'availability_range' => 0,
        ]);

        $confirmationUrl = route('provider.register.confirm', ['confirmation_code', $provider->confirmation_code]);

        if ($request->language === 'ar') {
            Mail::to($provider->email)->send(new HelloMailArabic($provider, $confirmationUrl));
        } else {
            Mail::to($provider->email)->send(new HelloMail($provider, $confirmationUrl));
        }

        return response()->json([
            'message' => 'Provider registered successfully!',
            'provider' => $provider,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:providers,email',
            'password' => 'required',
        ], [
            'email.required' => 'The email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.exists' => 'This email does not exist in our records.',
            'password.required' => 'The password is required.',
        ]);


        if (Auth::guard('provider')->attempt($request->only('email', 'password'))) {
            $provider = Auth::guard('provider')->user();
            $token = $provider->createToken('main')->plainTextToken;

            return response()->json([
                'message' => 'Provider retrieved successfully',
                'provider' => $provider,
                'token' => $token,
            ], 200);
        }

        return response()->json(__('auth.failed'), 401);
    }

    public function logout(Request $request)
    {
        $provider = $request->user();

        if (!$provider) {
            return response()->json([
                'message' => 'Provider not found',
            ], 404);
        }

        $provider->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Provider logout successfully',
        ], 201);
    }

    public function confirmEmail($confirmation_code)
    {
        $provider = Provider::where('confirmation_code', $confirmation_code)->first();

        if (!$provider) {
            return redirect()->route('login')->with('error', 'Invalid confirmation code!');
        }

        if ($provider->email_verified_at) {
            return redirect()->route('login')->with('success', __('lang.confirmation.confirmation_already_confirmed'));
        }

        $provider->email_verified_at = now();
        $provider->save();

        // Redirect to a custom page (dashboard, success page, etc.)
        return redirect()->route('login')->with('success', __('lang.confirmation.confirmation_successful'));
    }

    public function me(Request $request)
    {
        $user = Auth::guard('provider')->user();

        $schedule = $user?->schedule?->data
            ? json_decode($user->schedule->data, true)
            : [];
        $response = [];

        if (!empty($schedule)) {
            foreach ($schedule as $day => $times) {
                if ($times) {
                    $response[] = [
                        'day' => $day,
                        'working_hours' => $times['start'] . ' - ' . $times['end'],
                    ];
                } else {
                    $response[] = [
                        'day' => $day,
                        'working_hours' => 'Closed',
                    ];
                }
            }
        }

        return response()->json([
            'user_type' => 'provider',
            'data' => [
                'user_type' => 'provider',
                'image_path' => user_image($user),
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'category_id' => $user->category_id,
                'provider_type_id' => $user->provider_type_id,
                'description' => $user->description,
                'phone_number' => $user->phone_number,
                'mobile_number' => $user->mobile_number,
                'email_verified_at' => $user->email_verified_at,
                'available' => $user->available,
                'featured' => $user->featured,
                'accepted' => $user->accepted,
                'availability_range' => $user->availability_range,
                'schedules' => $schedule ? $response : [],
                'subscriptions' => $user->subscriptions?->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'provider_id' => $s->provider_id,
                        'pack_id' => $s->pack_id,
                    ];
                }),
            ]
        ]);
    }
}
