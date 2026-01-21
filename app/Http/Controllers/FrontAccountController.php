<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class FrontAccountController extends Controller
{
    public function dashboard()
    {
        return view('frontend.account.dashboard');
    }

    public function profile()
    {
        return view('frontend.account.profile');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'telephone_number' => ['nullable', 'string', 'max:255'],
        ]);

        // Update name field as combination of first and last name
        $validated['name'] = $validated['first_name'].' '.$validated['last_name'];

        $request->user()->update($validated);

        return back()->with('success', 'Profiel succesvol bijgewerkt.');
    }

    public function password()
    {
        return view('frontend.account.password');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Wachtwoord succesvol gewijzigd.');
    }

    public function email()
    {
        return view('frontend.account.email');
    }

    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$request->user()->id],
        ]);

        $request->user()->update([
            'email' => $validated['email'],
            'email_verified_at' => null,
        ]);

        return back()->with('success', 'E-mailadres succesvol gewijzigd. Controleer uw inbox voor een verificatie e-mail.');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'U bent succesvol uitgelogd.');
    }
}
