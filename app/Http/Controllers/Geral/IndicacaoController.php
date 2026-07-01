<?php

namespace App\Http\Controllers\Geral;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\CampanhaIndicacao;
use App\Models\Indicacao;
use Illuminate\Http\Request;

class IndicacaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Indicacao::with(['campanha', 'aluno.pessoa']);
        if ($request->filled('situacao')) {
            $query->where('situacao', $request->situacao);
        }
        $indicacoes = $query->orderByDesc('id')->paginate(20)->withQueryString();

        $stats = [
            'total' => Indicacao::count(),
            'pendentes' => Indicacao::where('situacao', 'pendente')->count(),
            'convertidas' => Indicacao::where('situacao', 'convertido')->count(),
        ];

        return view('geral.indicacoes.index', compact('indicacoes', 'stats'));
    }

    public function create()
    {
        return view('geral.indicacoes.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        Indicacao::create($this->validar($request));

        return redirect()->route('geral.indicacoes.index')->with('success', 'Indicação registrada.');
    }

    public function edit(Indicacao $indicaco)
    {
        return view('geral.indicacoes.form', $this->dados($indicaco));
    }

    public function update(Request $request, Indicacao $indicaco)
    {
        $indicaco->update($this->validar($request));

        return redirect()->route('geral.indicacoes.index')->with('success', 'Indicação atualizada.');
    }

    public function destroy(Indicacao $indicaco)
    {
        $indicaco->delete();

        return redirect()->route('geral.indicacoes.index')->with('success', 'Indicação removida.');
    }

    public function status(Request $request, Indicacao $indicaco)
    {
        $data = $request->validate(['situacao' => 'required|in:' . implode(',', array_keys(Indicacao::STATUS))]);
        $indicaco->update(['situacao' => $data['situacao']]);

        return back()->with('success', 'Status atualizado para ' . Indicacao::STATUS[$data['situacao']] . '.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'nome_indicado' => 'required|string|max:255',
            'telefone_indicado' => 'nullable|string|max:20',
            'email_indicado' => 'nullable|email|max:255',
            'campanha_id' => 'nullable|exists:campanhas_indicacao,id',
            'situacao' => 'required|in:' . implode(',', array_keys(Indicacao::STATUS)),
        ]);
    }

    private function dados(?Indicacao $indicacao): array
    {
        return [
            'indicacao' => $indicacao,
            'campanhas' => CampanhaIndicacao::where('ativo', true)->orderBy('nome')->get(),
            'alunos' => Aluno::with('pessoa')->get(),
        ];
    }
}
