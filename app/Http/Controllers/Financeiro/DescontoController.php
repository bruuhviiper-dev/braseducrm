<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\DescontoIncondicional;
use Illuminate\Http\Request;

class DescontoController extends Controller
{
    public function index()
    {
        $descontos = DescontoIncondicional::orderBy('nome')->paginate(20);
        return view('financeiro.descontos.index', compact('descontos'));
    }

    public function create()
    {
        return view('financeiro.descontos.form');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo', true);
        DescontoIncondicional::create($data);
        return redirect()->route('financeiro.descontos.index')->with('success', 'Desconto criado com sucesso.');
    }

    public function edit(DescontoIncondicional $desconto)
    {
        return view('financeiro.descontos.form', compact('desconto'));
    }

    public function update(Request $request, DescontoIncondicional $desconto)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo');
        $desconto->update($data);
        return redirect()->route('financeiro.descontos.index')->with('success', 'Desconto atualizado com sucesso.');
    }

    public function destroy(DescontoIncondicional $desconto)
    {
        $desconto->delete();
        return redirect()->route('financeiro.descontos.index')->with('success', 'Desconto removido com sucesso.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:percentual,valor',
            'valor' => 'required|numeric|min:0',
            'descricao' => 'nullable|string',
        ]);
    }
}
