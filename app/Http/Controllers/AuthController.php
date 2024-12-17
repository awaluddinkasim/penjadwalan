<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('auth');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->remember ? true : false;

        if (Auth::guard('user')->attempt($request->only(['email', 'password']), $remember)) {
            $request->session()->regenerate();

            return redirect()->intended();
        }
        if (Auth::attempt($request->only(['email', 'password']), $remember)) {
            $request->session()->regenerate();

            return redirect()->intended();
        }

        return back()->with('error', 'Email atau Password salah!');
    }

    public function logout() : RedirectResponse
    {
        Auth::guard('user')->logout();
        Auth::guard('admin')->logout();

        return to_route('login');
    }
}
