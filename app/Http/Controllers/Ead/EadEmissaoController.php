<?php

namespace App\Http\Controllers\Ead;

use App\Http\Controllers\Controller;
use App\Models\CursoEad;
use App\Models\MatriculaEad;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class EadEmissaoController extends Controller
{
    /** Página com as emissões EAD disponíveis e seus filtros. */
    public function index()
    {
        $cursos = CursoEad::orderBy('nome')->get();

        return view('ead.emissoes.index', compact('cursos'));
    }

    /** Emissão de Alunos Matriculados EAD (174). */
    public function alunosMatriculados(Request $request)
    {
        $query = MatriculaEad::with(['aluno.pessoa', 'cursoEad']);
        if ($request->filled('curso_ead_id')) {
            $query->where('curso_ead_id', $request->curso_ead_id);
        }
        if ($request->filled('situacao')) {
            $query->where('situacao', $request->situacao);
        }
        $matriculas = $query->orderByDesc('id')->get();

        $linhas = $matriculas->map(fn ($m) => [
            $m->aluno?->pessoa?->nome ?? '—',
            $m->cursoEad?->nome ?? '—',
            optional($m->data_matricula)->format('d/m/Y') ?? '—',
            number_format((float) $m->progresso, 0) . '%',
            ucfirst($m->situacao ?? '—'),
        ]);

        return $this->pdf('Emissão de Alunos Matriculados EAD', $this->filtroLabel($request),
            ['Aluno', 'Curso EAD', 'Matrícula', 'Progresso', 'Situação'], $linhas, 'alunos_matriculados_ead');
    }

    /** Emissão de Notas dos Alunos EAD (219). */
    public function notasAlunos(Request $request)
    {
        $query = MatriculaEad::with(['aluno.pessoa', 'cursoEad']);
        if ($request->filled('curso_ead_id')) {
            $query->where('curso_ead_id', $request->curso_ead_id);
        }
        $matriculas = $query->orderByDesc('id')->get();

        $linhas = $matriculas->map(fn ($m) => [
            $m->aluno?->pessoa?->nome ?? '—',
            $m->cursoEad?->nome ?? '—',
            number_format((float) $m->progresso, 0) . '%',
            ucfirst($m->situacao ?? '—'),
        ]);

        return $this->pdf('Emissão de Notas dos Alunos EAD', $this->filtroLabel($request),
            ['Aluno', 'Curso EAD', 'Aproveitamento', 'Situação'], $linhas, 'notas_alunos_ead');
    }

    private function filtroLabel(Request $request): ?string
    {
        $partes = [];
        if ($request->filled('curso_ead_id')) {
            $partes[] = 'Curso: ' . (CursoEad::find($request->curso_ead_id)?->nome ?? '—');
        }
        if ($request->filled('situacao')) {
            $partes[] = 'Situação: ' . ucfirst($request->situacao);
        }

        return $partes ? implode(' | ', $partes) : null;
    }

    private function pdf(string $titulo, ?string $subtitulo, array $colunas, $linhas, string $arquivo)
    {
        $linhas = collect($linhas)->map(fn ($l) => array_values((array) $l))->all();
        $pdf = Pdf::loadView('emissoes.academico.relatorio', compact('titulo', 'subtitulo', 'colunas', 'linhas'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream($arquivo . '.pdf');
    }
}
