<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Models\EstruturaPlano;
use App\Models\PlanoEnsino;
use App\Models\TurmaMontada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanoEnsinoController extends Controller
{
    /**
     * Lista os pares Turma Montada x Disciplina (derivados dos horários montados),
     * indicando se o plano de ensino já foi preenchido.
     */
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

        $preenchidos = PlanoEnsino::whereNotNull('estrutura_plano_id')
            ->get()
            ->keyBy(fn ($p) => $p->turma_montada_id . '-' . $p->disciplina_id);

        return view('academico.planos-ensino.index', compact('combos', 'preenchidos'));
    }

    public function preencher(Request $request, TurmaMontada $turma_montada, Disciplina $disciplina)
    {
        $plano = PlanoEnsino::firstOrCreate([
            'turma_montada_id' => $turma_montada->id,
            'disciplina_id' => $disciplina->id,
        ]);

        $estruturaId = $request->integer('estrutura') ?: $plano->estrutura_plano_id;
        $estrutura = $estruturaId ? EstruturaPlano::with('topicos')->find($estruturaId) : null;

        $conteudos = $plano->conteudos()->pluck('conteudo', 'topico_plano_id');
        $estruturas = EstruturaPlano::orderBy('nome')->get();

        return view('academico.planos-ensino.preencher', compact(
            'turma_montada', 'disciplina', 'plano', 'estrutura', 'estruturas', 'conteudos'
        ));
    }

    public function salvar(Request $request, TurmaMontada $turma_montada, Disciplina $disciplina)
    {
        $data = $request->validate([
            'estrutura_plano_id' => 'required|exists:estruturas_plano,id',
            'conteudo' => 'nullable|array',
        ]);

        $plano = PlanoEnsino::firstOrCreate([
            'turma_montada_id' => $turma_montada->id,
            'disciplina_id' => $disciplina->id,
        ]);

        DB::transaction(function () use ($plano, $data) {
            $plano->update(['estrutura_plano_id' => $data['estrutura_plano_id']]);

            $estrutura = EstruturaPlano::with('topicos')->find($data['estrutura_plano_id']);
            foreach ($estrutura->topicos as $topico) {
                $plano->conteudos()->updateOrCreate(
                    ['topico_plano_id' => $topico->id],
                    ['conteudo' => $data['conteudo'][$topico->id] ?? null]
                );
            }
        });

        return redirect()->route('academico.planos-ensino.index')
            ->with('success', 'Plano de ensino salvo com sucesso.');
    }
}
