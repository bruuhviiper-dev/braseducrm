<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\GradeHorario;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeHorarioController extends Controller
{
    public function index()
    {
        $grades = GradeHorario::with('turno')->withCount('aulas')->orderBy('nome')->paginate(20);

        return view('academico.grades-horario.index', compact('grades'));
    }

    public function create()
    {
        $turnos = Turno::orderBy('nome')->get();
        $grade = null;

        return view('academico.grades-horario.form', compact('turnos', 'grade'));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $this->salvar(new GradeHorario(), $data);

        return redirect()->route('academico.grades-horario.index')
            ->with('success', 'Grade de horário cadastrada com sucesso.');
    }

    public function edit(GradeHorario $grades_horario)
    {
        $turnos = Turno::orderBy('nome')->get();
        $grade = $grades_horario->load('aulas');

        return view('academico.grades-horario.form', compact('turnos', 'grade'));
    }

    public function update(Request $request, GradeHorario $grades_horario)
    {
        $data = $this->validar($request);
        $this->salvar($grades_horario, $data);

        return redirect()->route('academico.grades-horario.index')
            ->with('success', 'Grade de horário atualizada com sucesso.');
    }

    public function destroy(GradeHorario $grades_horario)
    {
        $grades_horario->delete();

        return redirect()->route('academico.grades-horario.index')
            ->with('success', 'Grade de horário removida.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'turno_id' => 'required|exists:turnos,id',
            'ativo' => 'nullable|boolean',
            'dias_semana' => 'nullable|array',
            'dias_semana.*' => 'integer|min:0|max:6',
            'aulas' => 'nullable|array',
            'aulas.*.hora_inicio' => 'required|date_format:H:i',
            'aulas.*.hora_fim' => 'required|date_format:H:i',
            'aulas.*.hora_aula' => 'nullable|date_format:H:i',
            'aulas.*.tipo' => 'nullable|in:aula,intervalo',
        ], [], [
            'aulas.*.hora_inicio' => 'hora de início',
            'aulas.*.hora_fim' => 'hora de fim',
        ]);
    }

    private function salvar(GradeHorario $grade, array $data): void
    {
        $aulas = collect($data['aulas'] ?? [])
            ->filter(fn ($a) => !empty($a['hora_inicio']) && !empty($a['hora_fim']))
            ->values();

        // grades_horario.hora_inicio/hora_fim são NOT NULL: derivamos do conjunto de aulas
        $horaInicio = $aulas->min('hora_inicio') ?: '00:00';
        $horaFim = $aulas->max('hora_fim') ?: '00:00';

        DB::transaction(function () use ($grade, $data, $aulas, $horaInicio, $horaFim) {
            $grade->fill([
                'nome' => $data['nome'],
                'turno_id' => $data['turno_id'],
                'ativo' => (bool) ($data['ativo'] ?? false),
                'dias_semana' => !empty($data['dias_semana']) ? implode(',', array_unique(array_map('intval', $data['dias_semana']))) : null,
                'hora_inicio' => $horaInicio,
                'hora_fim' => $horaFim,
            ])->save();

            $grade->aulas()->delete();
            foreach ($aulas as $i => $a) {
                $grade->aulas()->create([
                    'hora_inicio' => $a['hora_inicio'],
                    'hora_fim' => $a['hora_fim'],
                    'hora_aula' => $a['hora_aula'] ?? null,
                    'tipo' => $a['tipo'] ?? 'aula',
                    'ordem' => $i,
                ]);
            }
        });
    }
}
