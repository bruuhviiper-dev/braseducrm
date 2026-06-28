<?php

namespace App\Http\Controllers;

use App\Models\Requerimento;
use App\Models\TipoRequerimento;
use App\Models\Aluno;
use Illuminate\Http\Request;

class RequerimentoController extends Controller
{
    public function index()
    {
        $requerimentos = Requerimento::with(['aluno', 'tipoRequerimento'])
            ->orderBy('id', 'desc')->paginate(20);
        return view('administrativo.requerimentos.index', compact('requerimentos'));
    }

    public function create()
    {
        $alunos = Aluno::with('pessoa')->get();
        $tipos = TipoRequerimento::where('ativo', true)->orderBy('nome')->get();
        return view('administrativo.requerimentos.form', compact('alunos', 'tipos'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['operador_id'] = auth()->id();
        Requerimento::create($data);
        return redirect()->route('requerimentos.index')->with('success', 'Requerimento criado com sucesso.');
    }

    public function edit(Requerimento $requerimento)
    {
        $alunos = Aluno::with('pessoa')->get();
        $tipos = TipoRequerimento::where('ativo', true)->orderBy('nome')->get();
        return view('administrativo.requerimentos.form', compact('requerimento', 'alunos', 'tipos'));
    }

    public function update(Request $request, Requerimento $requerimento)
    {
        $data = $this->validateData($request);
        $requerimento->update($data);
        return redirect()->route('requerimentos.index')->with('success', 'Requerimento atualizado com sucesso.');
    }

    public function destroy(Requerimento $requerimento)
    {
        $requerimento->delete();
        return redirect()->route('requerimentos.index')->with('success', 'Requerimento removido com sucesso.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'tipo_requerimento_id' => 'required|exists:tipos_requerimento,id',
            'descricao' => 'nullable|string',
            'situacao' => 'required|in:pendente,aprovado,reprovado,cancelado,entregue',
            'observacoes' => 'nullable|string',
        ]);
    }
}
