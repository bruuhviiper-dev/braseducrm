<?php

namespace App\Http\Controllers\Biblioteca;

use App\Http\Controllers\Controller;
use App\Models\Exemplar;
use App\Models\MovimentacaoExemplar;
use Barryvdh\DomPDF\Facade\Pdf;

class BibliotecaEmissaoController extends Controller
{
    /** Emissão de Etiquetas (283). */
    public function etiquetas()
    {
        $linhas = Exemplar::with('obra')->orderBy('codigo')->get()
            ->map(fn ($e) => [$e->codigo ?? ('#' . $e->id), $e->obra?->titulo ?? '—']);

        return $this->pdf('Emissão de Etiquetas', 'Etiquetas dos exemplares', ['Código', 'Obra'], $linhas, 'etiquetas');
    }

    /** Emissão de Exemplares (284). */
    public function exemplares()
    {
        $linhas = Exemplar::with('obra', 'biblioteca')->orderByDesc('id')->get()
            ->map(fn ($e) => [
                $e->codigo ?? ('#' . $e->id),
                $e->obra?->titulo ?? '—',
                $e->biblioteca?->nome ?? '—',
                ucfirst($e->situacao),
            ]);

        return $this->pdf('Emissão de Exemplares', null, ['Código', 'Obra', 'Biblioteca', 'Situação'], $linhas, 'exemplares');
    }

    /** Emissão de Movimentações (285). */
    public function movimentacoes()
    {
        $linhas = MovimentacaoExemplar::with('exemplar.obra', 'pessoa')->orderByDesc('id')->get()
            ->map(fn ($m) => [
                $m->exemplar?->obra?->titulo ?? '—',
                $m->pessoa?->nome ?? '—',
                optional($m->data_emprestimo)->format('d/m/Y') ?? '—',
                optional($m->data_devolucao)->format('d/m/Y') ?? '—',
                ucfirst($m->situacao),
                'R$ ' . number_format($m->multa, 2, ',', '.'),
            ]);

        return $this->pdf('Emissão de Movimentações', null,
            ['Obra', 'Pessoa', 'Empréstimo', 'Devolução', 'Situação', 'Multa'], $linhas, 'movimentacoes_biblioteca');
    }

    private function pdf(string $titulo, ?string $subtitulo, array $colunas, $linhas, string $arquivo)
    {
        $linhas = collect($linhas)->map(fn ($l) => array_values((array) $l))->all();
        $pdf = Pdf::loadView('emissoes.academico.relatorio', compact('titulo', 'subtitulo', 'colunas', 'linhas'))
            ->setPaper('a4', 'landscape');
        return $pdf->stream($arquivo . '.pdf');
    }
}
