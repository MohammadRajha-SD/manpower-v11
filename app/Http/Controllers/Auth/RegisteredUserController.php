<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Mail\HelloMail;
use App\Mail\HelloMailArabic;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function confirmEmail($confirmation_code)
    {
        $user = User::where('confirmation_code', $confirmation_code)->first();

        if (!$user) {
            return redirect(env('FRONTEND_URL', 'https://hpower.ae').'/sign-in')->with('error', 'Invalid confirmation code!');
        }

        if ($user->email_verified_at) {
            return redirect(env('FRONTEND_URL', 'https://hpower.ae').'/sign-in')->with('success', __('lang.confirmation.confirmation_already_confirmed'));
        }

        $user->email_verified_at = now();
        $user->confirmation_code = null;
        $user->save();

     
        return redirect(env('FRONTEND_URL', 'https://hpower.ae'))->with('success', __('lang.confirmation.confirmation_successful'));
    }
    
    public function verifyEmail(Request $request)
    {

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User with this email does not exist.',
            ], 404);
        }
    
        if ($user->email_verified_at) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email is already verified.',
            ], 200);
        }
    
        if (!$user->confirmation_code) {
            $user->confirmation_code = Str::random(30);
            $user->save();
        }
    
        $confirmationUrl = url('register/confirm/' . $user->confirmation_code);
    
        try{

            if($request->lang == 'ar'){
                Mail::to($user->email)->send(new HelloMailArabic($user, $confirmationUrl));

            }else{
                Mail::to($user->email)->send(new HelloMail($user, $confirmationUrl));
            }
        }catch(Exception $e){
            Log::info("Error " . $e->getMessage());
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'Verification email sent successfully.',
        ]);
    }


}
