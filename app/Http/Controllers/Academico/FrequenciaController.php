<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\TurmaMontada;
use App\Models\Disciplina;
use App\Models\Horario;
use App\Models\Matricula;
use App\Models\Frequencia;
use App\Models\Profissional;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrequenciaController extends Controller
{
    public function index(Request $request)
    {
        $professores = Profissional::with('pessoa')->where('ativo', true)->get();
        $turmasMontadas = TurmaMontada::with('turma')->orderBy('id', 'desc')->get();
        $disciplinas = Disciplina::where('ativo', true)->orderBy('nome')->get();

        $roster = null;
        if ($request->filled(['turma_montada_id', 'disciplina_id', 'inicio', 'fim'])) {
            $roster = $this->montarRoster($request);

            if ($request->boolean('export')) {
                return $this->exportarCsv($roster);
            }
        }

        return view('academico.frequencia.index', compact('professores', 'turmasMontadas', 'disciplinas', 'roster', 'request'));
    }

    private function exportarCsv(array $roster)
    {
        $datas = $roster['datas'];
        $filename = 'frequencia_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($roster, $datas) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM p/ Excel
            $header = array_merge(['Aluno'], array_map(fn ($d) => \Carbon\Carbon::parse($d)->format('d/m/Y'), $datas));
            fputcsv($out, $header, ';');
            foreach ($roster['matriculas'] as $m) {
                $row = [$m->aluno?->pessoa?->nome ?? 'Matrícula ' . $m->id];
                foreach ($datas as $dt) {
                    $st = $roster['registros']->get($m->id . '|' . $dt)?->status ?? '';
                    $row[] = ['presente' => 'P', 'ausente' => 'F', 'justificada' => 'J'][$st] ?? '';
                }
                fputcsv($out, $row, ';');
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /** Datas de aula no intervalo = dias entre início/fim que batem com os dias de semana da grade (horários). */
    private function datasDeAula(Request $request): array
    {
        $inicio = Carbon::parse($request->inicio)->startOfDay();
        $fim = Carbon::parse($request->fim)->startOfDay();
        if ($fim->lt($inicio)) {
            [$inicio, $fim] = [$fim, $inicio];
        }
        // no máximo ~180 dias para evitar explosão
        if ($inicio->diffInDays($fim) > 180) {
            $fim = $inicio->copy()->addDays(180);
        }

        $diasGrade = Horario::where('turma_montada_id', $request->turma_montada_id)
            ->where('disciplina_id', $request->disciplina_id)
            ->pluck('dia_semana')->unique()->all();

        $datas = [];
        for ($d = $inicio->copy(); $d->lte($fim); $d->addDay()) {
            // dayOfWeekIso: 1=Segunda .. 7=Domingo (igual ao nosso Horario::dia_semana)
            if (empty($diasGrade) || in_array($d->dayOfWeekIso, $diasGrade)) {
                $datas[] = $d->format('Y-m-d');
            }
        }
        // se a grade não gera nenhuma data, usa ao menos o início
        return $datas ?: [$inicio->format('Y-m-d')];
    }

    private function montarRoster(Request $request): array
    {
        $situacoes = $request->boolean('somente_ativos') ? ['ativa'] : ['ativa', 'concluida'];
        $matriculas = Matricula::with('aluno.pessoa')
            ->where('turma_montada_id', $request->turma_montada_id)
            ->whereIn('situacao', $situacoes)
            ->get();

        $datas = $this->datasDeAula($request);

        $registros = Frequencia::where('disciplina_id', $request->disciplina_id)
            ->whereIn('data', $datas)
            ->whereIn('matricula_id', $matriculas->pluck('id'))
            ->get()
            ->keyBy(fn ($f) => $f->matricula_id . '|' . Carbon::parse($f->data)->format('Y-m-d'));

        // conteúdo ministrado por data (compartilhado na turma/disciplina/data)
        $conteudos = [];
        foreach ($datas as $dt) {
            $conteudos[$dt] = optional($registros->first(fn ($f) => Carbon::parse($f->data)->format('Y-m-d') === $dt))->conteudo_ministrado ?? '';
        }

        return compact('matriculas', 'datas', 'registros', 'conteudos');
    }

    public function salvar(Request $request)
    {
        $data = $request->validate([
            'turma_montada_id' => 'required|exists:turmas_montadas,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'professor_id' => 'nullable|exists:profissionais,id',
            'inicio' => 'required|date',
            'fim' => 'required|date',
            'somente_ativos' => 'nullable',
            'conteudo' => 'nullable|array',          // conteudo[data] = texto
            'status' => 'nullable|array',            // status[matricula_id][data] = presente|ausente|justificada
        ]);

        DB::transaction(function () use ($data) {
            foreach (($data['status'] ?? []) as $matriculaId => $porData) {
                foreach ($porData as $dt => $status) {
                    Frequencia::updateOrCreate(
                        [
                            'matricula_id' => $matriculaId,
                            'disciplina_id' => $data['disciplina_id'],
                            'data' => $dt,
                        ],
                        [
                            'status' => $status,
                            'conteudo_ministrado' => $data['conteudo'][$dt] ?? null,
                            'lancado_por' => auth()->id(),
                        ]
                    );
                }
            }
        });

        return redirect()->route('academico.frequencia.index', $request->only(['professor_id', 'turma_montada_id', 'disciplina_id', 'inicio', 'fim', 'somente_ativos']))
            ->with('success', 'Frequência registrada com sucesso.');
    }
}
