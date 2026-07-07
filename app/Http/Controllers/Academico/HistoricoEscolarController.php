<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Models\HistoricoEscolar;
use App\Models\Matricula;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Histórico Escolar manual (docs do EDUQ, Ecrã 23 → Notas e Faltas → Histórico Escolar):
 * para alunos migrados de outra plataforma, a secretaria lança as disciplinas dos módulos
 * anteriores com a média obtida no sistema antigo e o status Aprovado/Dispensado,
 * consolidando o passado acadêmico sem recriar turmas e cronogramas.
 */
class HistoricoEscolarController extends Controller
{
    public function editar(Matricula $matricula)
    {
        $matricula->load(['aluno.pessoa', 'turma.matrizCurricular', 'turma.curso']);
        $itens = HistoricoEscolar::with(['disciplina', 'modulo'])
            ->where('matricula_id', $matricula->id)->orderBy('modulo_id')->get();

        // disciplinas da matriz do curso (sugestão), mas qualquer disciplina pode ser lançada
        $disciplinas = Disciplina::where('ativo', true)->orderBy('nome')->get();
        $modulos = Modulo::orderBy('nome')->get();

        return view('academico.matriculas.historico', compact('matricula', 'itens', 'disciplinas', 'modulos'));
    }

    public function salvar(Request $request, Matricula $matricula)
    {
        $data = $request->validate([
            'itens' => 'nullable|array',
            'itens.*.disciplina_id' => 'nullable|exists:disciplinas,id',
            'itens.*.modulo_id' => 'nullable|exists:modulos,id',
            'itens.*.media' => 'nullable|numeric|min:0|max:10',
            'itens.*.status' => 'nullable|in:aprovado,dispensado,reprovado,cursando',
            'itens.*.observacao' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($matricula, $data) {
            HistoricoEscolar::where('matricula_id', $matricula->id)->delete();
            foreach ($data['itens'] ?? [] as $i) {
                if (empty($i['disciplina_id'])) {
                    continue;
                }
                HistoricoEscolar::create([
                    'matricula_id' => $matricula->id,
                    'disciplina_id' => $i['disciplina_id'],
                    'modulo_id' => ($i['modulo_id'] ?? null) ?: null,
                    'media' => ($i['media'] ?? '') !== '' ? $i['media'] : null,
                    'status' => $i['status'] ?? 'aprovado',
                    'observacao' => $i['observacao'] ?? null,
                ]);
            }
        });

        return redirect()->route('academico.matriculas.historico', $matricula)
            ->with('success', 'Histórico escolar consolidado. Os módulos anteriores ficam registrados sem precisar recriar turmas e cronogramas.');
    }
}
