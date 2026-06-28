<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['login' => $credentials['login'], 'password' => $credentials['password'], 'ativo' => true], $request->boolean('remember'))) {
            $request->session()->regenerate();
            Auth::user()->update(['ultimo_acesso' => now()]);
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['login' => 'Login ou senha incorretos.'])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
