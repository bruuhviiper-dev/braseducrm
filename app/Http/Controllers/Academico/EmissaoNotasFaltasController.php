<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Academico\Concerns\EmiteRelatorio;
use App\Models\Disciplina;
use App\Models\Frequencia;
use App\Models\Matricula;
use App\Models\Nota;
use App\Models\TurmaMontada;
use Illuminate\Http\Request;

/**
 * 60 Emissão de Notas e Faltas — construtor de relatório (padrão EDUQ):
 * aba Geral (Turma Montada, Disciplinas, Situação, Alunos, Tipo da emissão,
 * "Incluir notas das avaliações?") | Layout de Página, export PDF/CSV/XLSX.
 */
class EmissaoNotasFaltasController extends Controller
{
    use EmiteRelatorio;

    public function index(Request $request)
    {
        return view('academico.emissoes.notas-faltas', [
            'turmasMontadas' => TurmaMontada::with('turma')->orderByDesc('id')->get(),
            'disciplinas' => Disciplina::orderBy('nome')->get(),
            'situacoes' => ['ativa', 'nao_confirmada', 'confirmada', 'trancada', 'cancelada', 'concluida'],
            'turmaMontadaId' => $request->turma_montada_id,
            'alunos' => $request->filled('turma_montada_id')
                ? Matricula::with('aluno.pessoa')->where('turma_montada_id', $request->turma_montada_id)->get()
                : collect(),
        ]);
    }

    public function emitir(Request $request)
    {
        $request->validate(['turma_montada_id' => 'required|exists:turmas_montadas,id']);
        $tm = TurmaMontada::with('turma')->findOrFail($request->turma_montada_id);
        $incluirNotas = $request->boolean('incluir_notas');

        $matriculas = Matricula::with('aluno.pessoa')
            ->where('turma_montada_id', $tm->id)
            ->when($request->filled('situacoes'), fn ($q) => $q->whereIn('situacao', (array) $request->situacoes))
            ->when($request->filled('alunos'), fn ($q) => $q->whereIn('id', (array) $request->alunos))
            ->get();

        $disciplinaIds = (array) $request->input('disciplinas', []);
        $notas = Nota::with('disciplina')->whereIn('matricula_id', $matriculas->pluck('id'))
            ->when(!empty($disciplinaIds), fn ($q) => $q->whereIn('disciplina_id', $disciplinaIds))
            ->get();

        $cabecalho = $incluirNotas
            ? ['Aluno', 'Disciplina', 'Nota', 'Faltas', 'Situação']
            : ['Aluno', 'Disciplina', 'Faltas', 'Situação'];

        $linhas = collect();
        foreach ($matriculas as $m) {
            $nm = $notas->where('matricula_id', $m->id);
            if ($nm->isEmpty()) {
                $linhas->push($incluirNotas
                    ? [$m->aluno?->pessoa?->nome ?? '—', '—', '—', '—', ucfirst($m->situacao)]
                    : [$m->aluno?->pessoa?->nome ?? '—', '—', '—', ucfirst($m->situacao)]);
                continue;
            }
            foreach ($nm as $n) {
                $faltas = Frequencia::where('matricula_id', $m->id)
                    ->where('disciplina_id', $n->disciplina_id)->where('status', 'ausente')->count();
                $nome = $m->aluno?->pessoa?->nome ?? '—';
                $disc = $n->disciplina?->nome ?? '—';
                $linhas->push($incluirNotas
                    ? [$nome, $disc, $n->nota !== null ? number_format($n->nota, 2, ',', '.') : '—', $faltas, ucfirst($m->situacao)]
                    : [$nome, $disc, $faltas, ucfirst($m->situacao)]);
            }
        }

        return $this->emitirRelatorio(
            (string) $request->input('formato', 'pdf'),
            'Emissão de Notas e Faltas',
            'Turma Montada: ' . ($tm->nome ?? $tm->turma?->nome ?? ('#' . $tm->id)),
            $cabecalho, $linhas->all(), 'notas_faltas',
            $request->input('orientacao', 'landscape'), $request->input('papel', 'a4')
        );
    }
}
