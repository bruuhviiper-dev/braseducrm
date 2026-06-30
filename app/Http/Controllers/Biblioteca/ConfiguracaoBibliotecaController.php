<?php

namespace App\Http\Controllers\Biblioteca;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoBiblioteca;
use Illuminate\Http\Request;

class ConfiguracaoBibliotecaController extends Controller
{
    public function index()
    {
        $config = ConfiguracaoBiblioteca::current();

        return view('biblioteca.configuracao', compact('config'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'max_emprestimos' => 'required|integer|min:0',
            'dias_devolucao' => 'required|integer|min:0',
            'max_renovacoes' => 'required|integer|min:0',
            'dias_reserva' => 'required|integer|min:0',
            'max_reservas' => 'required|integer|min:0',
            'aplicar_multa' => 'nullable|boolean',
            'valor_diario' => 'nullable|numeric|min:0',
            'categoria_titulo' => 'nullable|string|max:255',
            'forma_pagamento' => 'nullable|string|max:255',
        ]);
        $data['aplicar_multa'] = $request->boolean('aplicar_multa');

        ConfiguracaoBiblioteca::current()->update($data);

        return redirect()->route('biblioteca.configuracao.index')->with('success', 'Configuração da biblioteca salva.');
    }
}
