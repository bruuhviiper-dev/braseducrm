<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Models\ProgramacaoAvaliacao;
use App\Models\TabelaAvaliacao;
use App\Models\TurmaMontada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramacaoAvaliacaoController extends Controller
{
    /** Lista os pares Turma Montada x Disciplina (dos horários montados) com a tabela programada. */
    public function index()
    {
        $combos = DB::table('horarios')
            ->join('turmas_montadas', 'horarios.turma_montada_id', '=', 'turmas_montadas.id')
            ->join('disciplinas', 'horarios.disciplina_id', '=', 'disciplinas.id')
            ->select('horarios.turma_montada_id', 'horarios.disciplina_id')
            ->selectRaw('MAX(turmas_montadas.nome) as turma_nome')
            ->selectRaw('MAX(disciplinas.nome) as disciplina_nome')
            ->groupBy('horarios.turma_montada_id', 'horarios.disciplina_id')
            ->orderBy('turma_nome')
            ->paginate(20);

        $programacoes = ProgramacaoAvaliacao::with('tabelaAvaliacao')
            ->get()
            ->keyBy(fn ($p) => $p->turma_montada_id . '-' . $p->disciplina_id);

        return view('academico.programacoes-avaliacao.index', compact('combos', 'programacoes'));
    }

    public function editar(TurmaMontada $turma_montada, Disciplina $disciplina)
    {
        $programacao = ProgramacaoAvaliacao::firstOrNew([
            'turma_montada_id' => $turma_montada->id,
            'disciplina_id' => $disciplina->id,
        ]);
        $tabelas = TabelaAvaliacao::orderBy('nome')->get();

        return view('academico.programacoes-avaliacao.editar', compact('turma_montada', 'disciplina', 'programacao', 'tabelas'));
    }

    public function salvar(Request $request, TurmaMontada $turma_montada, Disciplina $disciplina)
    {
        $data = $request->validate([
            'tabela_avaliacao_id' => 'required|exists:tabelas_avaliacao,id',
            'data_avaliacao' => 'nullable|date',
        ]);

        ProgramacaoAvaliacao::updateOrCreate(
            ['turma_montada_id' => $turma_montada->id, 'disciplina_id' => $disciplina->id],
            $data
        );

        return redirect()->route('academico.programacoes-avaliacao.index')
            ->with('success', 'Programação de avaliação salva com sucesso.');
    }
}
