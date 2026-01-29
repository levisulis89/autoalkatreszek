<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $r)
    {
        $data = $r->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($data, $r->boolean('remember'))) {
            $r->session()->regenerate();
            return redirect()->intended(route('catalog.index'));
        }

        return back()->withErrors(['email' => 'Hibás email vagy jelszó.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $r)
    {
        $data = $r->validate([
            'name' => ['nullable','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','min:6','confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'] ?: 'Felhasználó',
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $r->session()->regenerate();

        return redirect()->route('catalog.index');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect()->route('catalog.index');
    }
}
