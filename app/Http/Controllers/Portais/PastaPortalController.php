<?php

namespace App\Http\Controllers\Portais;

use App\Http\Controllers\Controller;
use App\Models\PastaPortal;
use Illuminate\Http\Request;

class PastaPortalController extends Controller
{
    public function index()
    {
        $pastas = PastaPortal::withCount('publicacoes')->orderBy('ordem')->paginate(20);
        return view('portais.pastas.index', compact('pastas'));
    }

    public function create()
    {
        return view('portais.pastas.form');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo', true);
        PastaPortal::create($data);
        return redirect()->route('portais.pastas.index')->with('success', 'Pasta criada com sucesso.');
    }

    public function edit(PastaPortal $pasta)
    {
        return view('portais.pastas.form', compact('pasta'));
    }

    public function update(Request $request, PastaPortal $pasta)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo');
        $pasta->update($data);
        return redirect()->route('portais.pastas.index')->with('success', 'Pasta atualizada com sucesso.');
    }

    public function destroy(PastaPortal $pasta)
    {
        $pasta->delete();
        return redirect()->route('portais.pastas.index')->with('success', 'Pasta removida com sucesso.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ordem' => 'nullable|integer|min:0',
        ]);
    }
}
