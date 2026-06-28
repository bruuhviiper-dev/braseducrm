<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing');
    }

    public function contato(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email',
            'telefone' => 'required',
            'instituicao' => 'required',
            'mensagem' => 'required',
        ]);

        return back()->with('success', 'Obrigado! Entraremos em contato em breve.');
    }
}
