<?php

namespace App\Http\Controllers\MatriculaOnline;

use App\Http\Controllers\Controller;
use App\Models\Inscricao;
use App\Models\AberturaMatriculaOnline;
use Illuminate\Http\Request;

class InscricaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Inscricao::with(['abertura', 'cupom']);

        if ($request->filled('situacao')) {
            $query->where('situacao', $request->situacao);
        }

        $inscricoes = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();
        return view('matricula-online.inscricoes.index', compact('inscricoes'));
    }

    public function create()
    {
        $aberturas = AberturaMatriculaOnline::where('ativo', true)->orderBy('nome')->get();
        return view('matricula-online.inscricoes.form', compact('aberturas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'abertura_matricula_id' => 'required|exists:aberturas_matricula_online,id',
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14',
        ]);
        $data['situacao'] = 'pendente';
        Inscricao::create($data);
        return redirect()->route('matricula-online.inscricoes.index')->with('success', 'Inscrição registrada com sucesso.');
    }

    public function update(Request $request, Inscricao $inscricao)
    {
        $data = $request->validate([
            'situacao' => 'required|in:pendente,aprovada,cancelada,matriculada',
            'pagamento_confirmado' => 'boolean',
            'contrato_assinado' => 'boolean',
        ]);
        $data['pagamento_confirmado'] = $request->boolean('pagamento_confirmado');
        $data['contrato_assinado'] = $request->boolean('contrato_assinado');
        $inscricao->update($data);
        return redirect()->route('matricula-online.inscricoes.index')->with('success', 'Inscrição atualizada com sucesso.');
    }

    public function destroy(Inscricao $inscricao)
    {
        $inscricao->delete();
        return redirect()->route('matricula-online.inscricoes.index')->with('success', 'Inscrição removida com sucesso.');
    }
}
