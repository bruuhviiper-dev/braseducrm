<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Documento;
use App\Models\EntregaDocumento;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntregaDocumentoController extends Controller
{
    /** Entrega de Documentos (19): lista de matrículas/alunos. */
    public function index()
    {
        $matriculas = Matricula::with('aluno.pessoa', 'turma.curso')->orderByDesc('id')->paginate(20);

        return view('academico.entregas-documento.index', compact('matriculas'));
    }

    /** Gerenciar a entrega dos documentos de uma matrícula. */
    public function gerenciar(Matricula $matricula)
    {
        $matricula->load('aluno.pessoa', 'turma.curso');
        $documentos = $this->documentosDaMatricula($matricula);
        $entregas = EntregaDocumento::where('matricula_id', $matricula->id)->get()->keyBy('documento_id');

        return view('academico.entregas-documento.gerenciar', compact('matricula', 'documentos', 'entregas'));
    }

    public function salvar(Request $request, Matricula $matricula)
    {
        $entregues = collect($request->input('entregue', []))->keys();
        $datas = $request->input('data_entrega', []);
        $documentos = $this->documentosDaMatricula($matricula);

        DB::transaction(function () use ($matricula, $documentos, $entregues, $datas) {
            foreach ($documentos as $doc) {
                $entregue = $entregues->contains($doc->id);
                EntregaDocumento::updateOrCreate(
                    ['matricula_id' => $matricula->id, 'documento_id' => $doc->id],
                    [
                        'entregue' => $entregue,
                        'data_entrega' => $entregue ? ($datas[$doc->id] ?? now()->toDateString()) : null,
                    ]
                );
            }
        });

        return redirect()->route('academico.entregas-documento.index')
            ->with('success', 'Entregas atualizadas para ' . ($matricula->aluno?->pessoa?->nome ?? 'matrícula') . '.');
    }

    /** Consulta Documentos não Entregues (102): relatório de pendências. */
    public function consultaPendentes(Request $request)
    {
        $cursos = Curso::orderBy('nome')->get();
        $pendencias = collect();

        if ($request->boolean('consultar')) {
            $query = Matricula::with('aluno.pessoa', 'turma.curso');
            if ($request->filled('curso_id')) {
                $query->whereHas('turma', fn ($q) => $q->where('curso_id', $request->curso_id));
            }
            if ($request->filled('situacao')) {
                $query->where('situacao', $request->situacao);
            }

            foreach ($query->get() as $m) {
                $obrigatorios = $this->documentosDaMatricula($m)->where('obrigatorio', true);
                $entreguesIds = EntregaDocumento::where('matricula_id', $m->id)->where('entregue', true)->pluck('documento_id');
                $faltantes = $obrigatorios->whereNotIn('id', $entreguesIds);
                if ($faltantes->isNotEmpty()) {
                    $pendencias->push([
                        'matricula' => $m,
                        'faltantes' => $faltantes->pluck('nome')->implode(', '),
                    ]);
                }
            }
        }

        return view('academico.entregas-documento.consulta', compact('cursos', 'pendencias', 'request'));
    }

    private function documentosDaMatricula(Matricula $matricula)
    {
        $cursoId = $matricula->turma?->curso_id;

        return Documento::where('ativo', true)
            ->where(fn ($q) => $q->whereNull('curso_id')->orWhere('curso_id', $cursoId))
            ->orderBy('nome')->get();
    }
}
