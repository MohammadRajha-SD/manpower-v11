<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

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
            return redirect()->route('login')->with('error', 'Invalid confirmation code!');
        }

        if ($user->email_verified_at) {
            return redirect()->route('login')->with('success', __('lang.confirmation.confirmation_already_confirmed'));
        }

        $user->email_verified_at = now();
        $user->save();

        // Redirect to a custom page (dashboard, success page, etc.)
        return redirect()->route('login')->with('success', __('lang.confirmation.confirmation_successful'));
    }


}
