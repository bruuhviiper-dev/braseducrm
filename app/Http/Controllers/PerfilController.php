<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PerfilController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(['grupoOperador', 'departamento']);
        return view('perfil.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'nome' => $request->nome,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required',
            'nova_senha' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->senha_atual, $user->password)) {
            return back()->withErrors(['senha_atual' => 'A senha atual esta incorreta.']);
        }

        $user->update([
            'password' => Hash::make($request->nova_senha),
        ]);

        return back()->with('success', 'Senha alterada com sucesso!');
    }
}
