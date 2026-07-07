<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Oportunidade;
use App\Models\PropostaCrm;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PropostaCrmController extends Controller
{
    public function index()
    {
        $oportunidades = Oportunidade::with(['interessado', 'produtoServico', 'consultor'])
            ->orderByDesc('id')->paginate(20);
        $propostas = PropostaCrm::with(['oportunidade.interessado', 'criadaPor', 'aprovadaPor'])
            ->orderByDesc('id')->get()->groupBy('oportunidade_id');

        return view('crm.propostas.index', compact('oportunidades', 'propostas'));
    }

    /**
     * Alçada de aprovação (docs do EDUQ): o operador tem um limite de desconto;
     * proposta com desconto acima do limite fica PENDENTE até o gestor aprovar,
     * e o PDF só sai depois da aprovação.
     */
    public function store(Request $request, Oportunidade $oportunidade)
    {
        $v = $request->validate([
            'valor' => 'required|numeric|min:0',
            'desconto_percentual' => 'nullable|numeric|min:0|max:100',
            'validade' => 'nullable|date',
        ]);

        $user = auth()->user();
        $desconto = (float) ($v['desconto_percentual'] ?? 0);
        $dentroAlcada = $user->limite_desconto === null || $desconto <= (float) $user->limite_desconto;

        PropostaCrm::create([
            'oportunidade_id' => $oportunidade->id,
            'titulo' => 'Proposta: ' . ($oportunidade->titulo ?: ($oportunidade->interessado?->nome ?? 'oportunidade #' . $oportunidade->id)),
            'valor' => $v['valor'],
            'desconto_percentual' => $desconto ?: null,
            'validade' => $v['validade'] ?? null,
            'situacao' => 'rascunho',
            'aprovacao' => $dentroAlcada ? 'nao_requer' : 'pendente',
            'criada_por' => $user->id,
        ]);

        return back()->with('success', $dentroAlcada
            ? 'Proposta criada. Pode emitir o PDF.'
            : "Proposta criada com desconto de {$desconto}% ACIMA da sua alçada (limite {$user->limite_desconto}%): aguardando aprovação do gestor para emitir.");
    }

    /** Gestor (sem limite de desconto) aprova ou reprova a proposta pendente. */
    public function aprovar(Request $request, PropostaCrm $proposta)
    {
        $user = auth()->user();
        if ($user->limite_desconto !== null && !$user->is_admin) {
            return back()->with('error', 'Apenas gestores (operador sem limite de alçada) podem aprovar propostas.');
        }

        $v = $request->validate([
            'decisao' => 'required|in:aprovada,reprovada',
            'motivo_reprovacao' => 'required_if:decisao,reprovada|nullable|string|max:255',
        ], [
            'motivo_reprovacao.required_if' => 'Informe o motivo da reprovação da proposta.',
        ]);

        $proposta->update([
            'aprovacao' => $v['decisao'],
            'aprovada_por' => $user->id,
            'motivo_reprovacao' => $v['decisao'] === 'reprovada' ? $v['motivo_reprovacao'] : null,
        ]);

        return back()->with('success', $v['decisao'] === 'aprovada'
            ? 'Proposta aprovada: o PDF já pode ser emitido.'
            : 'Proposta reprovada.');
    }

    public function gerar(Oportunidade $oportunidade)
    {
        $oportunidade->load(['interessado', 'produtoServico', 'consultor', 'curso']);

        // trava da alçada: se a última proposta formal está pendente/reprovada, o PDF não sai
        $proposta = PropostaCrm::where('oportunidade_id', $oportunidade->id)->orderByDesc('id')->first();
        if ($proposta && in_array($proposta->aprovacao, ['pendente', 'reprovada'], true)) {
            return back()->with('error', $proposta->aprovacao === 'pendente'
                ? 'Proposta com desconto acima da alçada: aguarde a aprovação do gestor para emitir o PDF.'
                : 'Proposta reprovada pelo gestor (' . ($proposta->motivo_reprovacao ?: 'sem motivo registrado') . '). Crie uma nova proposta.');
        }

        $pdf = Pdf::loadView('crm.propostas.pdf', compact('oportunidade', 'proposta'))->setPaper('a4', 'portrait');

        return $pdf->stream('proposta_' . $oportunidade->id . '.pdf');
    }
}
