<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Calendario;
use App\Models\CalendarioEvento;
use App\Support\FeriadosBrasil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarioController extends Controller
{
    public function index()
    {
        $calendarios = Calendario::withCount(['eventos as dias_letivos_count' => function ($q) {
            $q->where('dia_letivo', true);
        }])->orderByDesc('ano')->paginate(20);

        return view('academico.calendarios.index', compact('calendarios'));
    }

    public function create()
    {
        return view('academico.calendarios.create');
    }

    /**
     * "Carregar dias do Ano" (EDUQ 35): cria o calendário e gera TODOS os dias do ano.
     * Regra: dias úteis (seg-sex) nascem LETIVOS; fins de semana e feriados nascem NÃO-LETIVOS,
     * com a observação do feriado já preenchida.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'ano' => 'required|integer|min:1900|max:2100|unique:calendarios,ano',
        ]);

        $ano = (int) $data['ano'];
        $feriados = FeriadosBrasil::doAno($ano);

        $calendario = DB::transaction(function () use ($ano, $feriados) {
            $calendario = Calendario::create([
                'nome' => "Calendário {$ano}",
                'ano' => $ano,
            ]);

            $inicio = Carbon::createFromDate($ano, 1, 1)->startOfDay();
            $fim = Carbon::createFromDate($ano, 12, 31)->startOfDay();

            $linhas = [];
            for ($dia = $inicio->copy(); $dia->lte($fim); $dia->addDay()) {
                $iso = $dia->format('Y-m-d');
                $feriado = $feriados[$iso] ?? null;
                $fimDeSemana = $dia->isWeekend();

                $linhas[] = [
                    'calendario_id' => $calendario->id,
                    'data' => $iso,
                    'descricao' => $feriado ?? '',
                    'dia_letivo' => !$feriado && !$fimDeSemana,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // insere em lotes (SQLite tem limite de variáveis por statement)
            foreach (array_chunk($linhas, 100) as $lote) {
                CalendarioEvento::insert($lote);
            }

            return $calendario;
        });

        return redirect()->route('academico.calendarios.edit', $calendario)
            ->with('success', "Calendário {$ano} carregado. Marque os dias letivos e salve.");
    }

    public function edit(Calendario $calendario)
    {
        $eventos = $calendario->eventos()->orderBy('data')->get()->groupBy(fn ($e) => $e->data->month);

        return view('academico.calendarios.edit', compact('calendario', 'eventos'));
    }

    /**
     * Salva a marcação: dias presentes em letivos[] = LETIVOS, os demais = NÃO-LETIVOS.
     * Observação por dia em observacao[evento_id].
     */
    public function update(Request $request, Calendario $calendario)
    {
        $letivos = collect($request->input('letivos', []))->map(fn ($v) => (int) $v)->flip();
        $observacoes = $request->input('observacao', []);

        DB::transaction(function () use ($calendario, $letivos, $observacoes) {
            foreach ($calendario->eventos as $evento) {
                $evento->update([
                    'dia_letivo' => $letivos->has($evento->id),
                    'descricao' => $observacoes[$evento->id] ?? '',
                ]);
            }
        });

        return redirect()->route('academico.calendarios.index')
            ->with('success', "Calendário {$calendario->ano} salvo com sucesso.");
    }

    public function destroy(Calendario $calendario)
    {
        $calendario->delete();

        return redirect()->route('academico.calendarios.index')
            ->with('success', 'Calendário removido.');
    }
}
