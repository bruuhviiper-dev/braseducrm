<?php

namespace App\Http\Controllers\Ged;

use App\Http\Controllers\Controller;
use App\Models\ClassificacaoGed;
use Illuminate\Http\Request;

class ClassificacaoGedController extends Controller
{
    public function index()
    {
        $classificacoes = ClassificacaoGed::with('pai')->withCount('documentos')->orderBy('nome')->paginate(20);
        return view('ged.classificacoes.index', compact('classificacoes'));
    }

    public function create()
    {
        $pais = ClassificacaoGed::orderBy('nome')->get();
        return view('ged.classificacoes.form', compact('pais'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo', true);
        ClassificacaoGed::create($data);
        return redirect()->route('ged.classificacoes.index')->with('success', 'Classificação criada com sucesso.');
    }

    public function edit(ClassificacaoGed $classificaco)
    {
        $classificacao = $classificaco;
        $pais = ClassificacaoGed::where('id', '!=', $classificaco->id)->orderBy('nome')->get();
        return view('ged.classificacoes.form', compact('classificacao', 'pais'));
    }

    public function update(Request $request, ClassificacaoGed $classificaco)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo');
        $classificaco->update($data);
        return redirect()->route('ged.classificacoes.index')->with('success', 'Classificação atualizada com sucesso.');
    }

    public function destroy(ClassificacaoGed $classificaco)
    {
        $classificaco->delete();
        return redirect()->route('ged.classificacoes.index')->with('success', 'Classificação removida com sucesso.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'pai_id' => 'nullable|exists:classificacoes_ged,id',
        ]);
    }
}
