<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\TurmaMontada;
use App\Models\Turma;
use App\Models\Modulo;
use App\Models\PeriodoLetivo;
use App\Models\Disciplina;
use App\Models\Profissional;
use App\Models\Sala;
use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MontagemTurmaController extends Controller
{
    public function index()
    {
        $turmasMontadas = TurmaMontada::with(['turma', 'modulo', 'periodoLetivo'])
            ->withCount('horarios')
            ->orderBy('id', 'desc')->paginate(20);
        return view('academico.montagem-turma.index', compact('turmasMontadas'));
    }

    public function create()
    {
        return view('academico.montagem-turma.form', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $turmaMontada = TurmaMontada::create([
            'turma_id' => $validated['turma_id'],
            'modulo_id' => $validated['modulo_id'] ?? null,
            'periodo_letivo_id' => $validated['periodo_letivo_id'] ?? null,
            'nome' => $validated['nome'] ?? null,
            'situacao' => $validated['situacao'],
        ]);
        $this->syncHorarios($turmaMontada, $validated['horarios'] ?? []);
        return redirect()->route('academico.montagem-turma.edit', $turmaMontada)
            ->with('success', 'Turma montada criada. Agora matricule os alunos abaixo.');
    }

    public function edit(TurmaMontada $montagem_turma)
    {
        $turmaMontada = $montagem_turma->load(['horarios.disciplina', 'horarios.profissional.pessoa', 'horarios.sala']);
        $matriculados = Matricula::with('aluno.pessoa')
            ->where('turma_montada_id', $turmaMontada->id)->get();
        $alunosDisponiveis = Aluno::with('pessoa')->where('ativo', true)->get();

        return view('academico.montagem-turma.form', array_merge($this->formData(), compact('turmaMontada', 'matriculados', 'alunosDisponiveis')));
    }

    public function update(Request $request, TurmaMontada $montagem_turma)
    {
        $validated = $this->validateData($request);
        $montagem_turma->update([
            'turma_id' => $validated['turma_id'],
            'modulo_id' => $validated['modulo_id'] ?? null,
            'periodo_letivo_id' => $validated['periodo_letivo_id'] ?? null,
            'nome' => $validated['nome'] ?? null,
            'situacao' => $validated['situacao'],
        ]);
        $this->syncHorarios($montagem_turma, $validated['horarios'] ?? []);
        return redirect()->route('academico.montagem-turma.edit', $montagem_turma)
            ->with('success', 'Turma montada atualizada com sucesso.');
    }

    public function destroy(TurmaMontada $montagem_turma)
    {
        $montagem_turma->horarios()->delete();
        $montagem_turma->delete();
        return redirect()->route('academico.montagem-turma.index')->with('success', 'Turma montada removida.');
    }

    /** Matricula um aluno na turma montada. */
    public function matricular(Request $request, TurmaMontada $montagem_turma)
    {
        $data = $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
        ]);

        $jaMatriculado = Matricula::where('turma_montada_id', $montagem_turma->id)
            ->where('aluno_id', $data['aluno_id'])->exists();
        if ($jaMatriculado) {
            return back()->with('error', 'Este aluno já está matriculado nesta turma montada.');
        }

        Matricula::create([
            'aluno_id' => $data['aluno_id'],
            'turma_id' => $montagem_turma->turma_id,
            'turma_montada_id' => $montagem_turma->id,
            'data_matricula' => now(),
            'situacao' => 'ativa',
        ]);

        return back()->with('success', 'Aluno matriculado na turma montada.');
    }

    public function desmatricular(TurmaMontada $montagem_turma, Matricula $matricula)
    {
        $matricula->delete();
        return back()->with('success', 'Aluno removido da turma montada.');
    }

    private function formData(): array
    {
        return [
            'turmas' => Turma::orderBy('nome')->get(),
            'modulos' => Modulo::orderBy('nome')->get(),
            'periodos' => PeriodoLetivo::orderBy('id', 'desc')->get(),
            'disciplinas' => Disciplina::where('ativo', true)->orderBy('nome')->get(),
            'profissionais' => Profissional::with('pessoa')->where('ativo', true)->get(),
            'salas' => Sala::where('ativo', true)->orderBy('nome')->get(),
            'diasSemana' => Horario::diasSemana(),
        ];
    }

    private function syncHorarios(TurmaMontada $turmaMontada, array $horarios): void
    {
        $turmaMontada->horarios()->delete();
        foreach ($horarios as $h) {
            if (empty($h['disciplina_id'])) {
                continue;
            }
            $turmaMontada->horarios()->create([
                'disciplina_id' => $h['disciplina_id'],
                'profissional_id' => $h['profissional_id'] ?? null,
                'sala_id' => $h['sala_id'] ?? null,
                'dia_semana' => $h['dia_semana'],
                'hora_inicio' => $h['hora_inicio'],
                'hora_fim' => $h['hora_fim'],
            ]);
        }
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'turma_id' => 'required|exists:turmas,id',
            'modulo_id' => 'nullable|exists:modulos,id',
            'periodo_letivo_id' => 'nullable|exists:periodos_letivos,id',
            'nome' => 'nullable|string|max:255',
            'situacao' => 'required|in:aberta,em_andamento,finalizada',
            'horarios' => 'nullable|array',
            'horarios.*.disciplina_id' => 'nullable|exists:disciplinas,id',
            'horarios.*.profissional_id' => 'nullable|exists:profissionais,id',
            'horarios.*.sala_id' => 'nullable|exists:salas,id',
            'horarios.*.dia_semana' => 'required_with:horarios.*.disciplina_id|integer|between:1,7',
            'horarios.*.hora_inicio' => 'required_with:horarios.*.disciplina_id',
            'horarios.*.hora_fim' => 'required_with:horarios.*.disciplina_id',
        ]);
    }
}
