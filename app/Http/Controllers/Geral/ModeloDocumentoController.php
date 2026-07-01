<?php

namespace App\Http\Controllers\Geral;

use App\Http\Controllers\Controller;
use App\Models\ModeloDocumento;
use Illuminate\Http\Request;

class ModeloDocumentoController extends Controller
{
    public function index()
    {
        $modelos = ModeloDocumento::orderBy('nome')->paginate(20);

        return view('geral.modelos-documento.index', compact('modelos'));
    }

    public function create()
    {
        return view('geral.modelos-documento.form', ['modelo' => null]);
    }

    public function store(Request $request)
    {
        ModeloDocumento::create($this->validar($request));

        return redirect()->route('geral.modelos-documento.index')->with('success', 'Modelo de documento criado.');
    }

    public function edit(ModeloDocumento $modelo)
    {
        return view('geral.modelos-documento.form', compact('modelo'));
    }

    public function update(Request $request, ModeloDocumento $modelo)
    {
        $modelo->update($this->validar($request));

        return redirect()->route('geral.modelos-documento.index')->with('success', 'Modelo atualizado.');
    }

    public function destroy(ModeloDocumento $modelo)
    {
        $modelo->delete();

        return redirect()->route('geral.modelos-documento.index')->with('success', 'Modelo removido.');
    }

    private function validar(Request $request): array
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:' . implode(',', array_keys(ModeloDocumento::TIPOS)),
            'conteudo' => 'required|string',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->boolean('ativo');

        return $data;
    }
}
