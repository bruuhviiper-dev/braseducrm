<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Academico\Concerns\EmiteRelatorio;
use App\Models\EmissaoLayout;
use App\Models\Frequencia;
use App\Models\Matricula;
use App\Models\Nota;
use App\Models\Profissional;
use App\Models\TurmaMontada;
use Illuminate\Http\Request;

/** 249 Emissão de Pendências de Notas e Faltas — report-builder (padrão EDUQ). */
class EmissaoPendenciasController extends Controller
{
    use EmiteRelatorio;

    private const FUNCAO = 249;

    private function catalogo(): array
    {
        return [
            'aluno' => ['Aluno', fn ($m) => $m->aluno?->pessoa?->nome ?? '—'],
            'turma' => ['Turma Montada', fn ($m) => $m->turmaMontada?->nome ?? $m->turma?->nome ?? '—'],
            'notas_pendentes' => ['Notas Pendentes', fn ($m) => (string) $m->_notas_pend],
            'freq_pendentes' => ['Frequências Pendentes', fn ($m) => (string) $m->_freq_pend],
            'situacao' => ['Situação', fn ($m) => ucfirst($m->situacao)],
        ];
    }

    private function colunasPadrao(): array
    {
        return ['aluno', 'turma', 'notas_pendentes', 'freq_pendentes'];
    }

    public function index(Request $request)
    {
        $layouts = EmissaoLayout::layoutsDe(self::FUNCAO);
        $layoutAtual = $request->filled('layout_id') ? $layouts->firstWhere('id', (int) $request->layout_id) : $layouts->firstWhere('padrao', true);

        return view('academico.emissoes.pendencias-notas-faltas', [
            'catalogo' => collect($this->catalogo())->map(fn ($v) => $v[0]),
            'colunasSel' => $layoutAtual?->colunas ?? $this->colunasPadrao(),
            'layouts' => $layouts,
            'layoutAtual' => $layoutAtual,
            'turmasMontadas' => TurmaMontada::with('turma')->orderByDesc('id')->get(),
            'professores' => Profissional::with('pessoa')->get()->sortBy(fn ($p) => $p->pessoa?->nome)->values(),
        ]);
    }

    public function emitir(Request $request)
    {
        $request->validate(['turma_montada_id' => 'required|exists:turmas_montadas,id']);
        $catalogo = $this->catalogo();
        $colunas = array_values(array_filter((array) $request->input('colunas', $this->colunasPadrao()), fn ($c) => isset($catalogo[$c]))) ?: $this->colunasPadrao();

        $matriculas = Matricula::with(['aluno.pessoa', 'turmaMontada', 'turma'])
            ->where('turma_montada_id', $request->turma_montada_id)->get();

        // pendências: alunos sem nota / sem frequência registrada
        foreach ($matriculas as $m) {
            $m->_notas_pend = $request->boolean('emitir_notas', true)
                ? Nota::where('matricula_id', $m->id)->whereNull('nota')->count()
                : 0;
            $m->_freq_pend = $request->boolean('emitir_freq', true)
                ? Frequencia::where('matricula_id', $m->id)->whereNull('status')->count()
                : 0;
        }

        $cabecalho = array_map(fn ($c) => $catalogo[$c][0], $colunas);
        $linhas = $matriculas->map(fn ($m) => array_map(fn ($c) => $catalogo[$c][1]($m), $colunas))->all();

        return $this->emitirRelatorio((string) $request->input('formato', 'pdf'), 'Emissão de Pendências de Notas e Faltas', null, $cabecalho, $linhas, 'pendencias_notas_faltas', $request->input('orientacao', 'landscape'), $request->input('papel', 'a4'));
    }
}
