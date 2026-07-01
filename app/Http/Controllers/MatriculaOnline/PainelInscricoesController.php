<?php

namespace App\Http\Controllers\MatriculaOnline;

use App\Http\Controllers\Controller;
use App\Models\Inscricao;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PainelInscricoesController extends Controller
{
    /** Painel de Inscrições Online (151). */
    public function index()
    {
        $porSituacao = Inscricao::selectRaw('situacao, COUNT(*) as total')->groupBy('situacao')->pluck('total', 'situacao');

        $stats = [
            'total' => Inscricao::count(),
            'pendentes' => $porSituacao['pendente'] ?? 0,
            'aprovadas' => $porSituacao['aprovada'] ?? 0,
            'matriculadas' => $porSituacao['matriculada'] ?? 0,
            'canceladas' => $porSituacao['cancelada'] ?? 0,
            'pagas' => Inscricao::where('pagamento_confirmado', true)->count(),
        ];

        $recentes = Inscricao::with('abertura')->orderByDesc('id')->limit(10)->get();

        return view('matricula-online.painel.index', compact('stats', 'recentes'));
    }

    /** Emissão de Inscrições (187). */
    public function emissao(Request $request)
    {
        $query = Inscricao::with('abertura');
        if ($request->filled('situacao')) {
            $query->where('situacao', $request->situacao);
        }
        $inscricoes = $query->orderByDesc('id')->get();

        $linhas = $inscricoes->map(fn ($i) => [
            $i->nome,
            $i->email ?? '—',
            $i->telefone ?? '—',
            $i->abertura?->nome ?? '—',
            ucfirst($i->situacao),
            $i->pagamento_confirmado ? 'Sim' : 'Não',
        ]);

        $titulo = 'Emissão de Inscrições';
        $subtitulo = $request->filled('situacao') ? 'Situação: ' . ucfirst($request->situacao) : 'Todas as situações';
        $colunas = ['Nome', 'E-mail', 'Telefone', 'Abertura', 'Situação', 'Pago'];
        $linhas = $linhas->map(fn ($l) => array_values((array) $l))->all();

        $pdf = Pdf::loadView('emissoes.academico.relatorio', compact('titulo', 'subtitulo', 'colunas', 'linhas'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('inscricoes.pdf');
    }
}
