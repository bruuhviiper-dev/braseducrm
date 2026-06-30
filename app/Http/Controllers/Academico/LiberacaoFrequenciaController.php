<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\LiberacaoFrequencia;
use App\Models\Profissional;
use App\Models\TurmaMontada;
use Illuminate\Http\Request;

class LiberacaoFrequenciaController extends Controller
{
    public function index()
    {
        $registros = LiberacaoFrequencia::with('turmaMontada.turma', 'profissional.pessoa')->orderByDesc('id')->paginate(20);

        return view('academico.liberacoes-frequencia.index', compact('registros'));
    }

    public function create()
    {
        return view('academico.liberacoes-frequencia.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        LiberacaoFrequencia::create($this->validar($request));

        return redirect()->route('academico.liberacoes-frequencia.index')
            ->with('success', 'Liberação de frequência cadastrada com sucesso.');
    }

    public function edit(LiberacaoFrequencia $liberacoes_frequencium)
    {
        return view('academico.liberacoes-frequencia.form', $this->dados($liberacoes_frequencium));
    }

    public function update(Request $request, LiberacaoFrequencia $liberacoes_frequencium)
    {
        $liberacoes_frequencium->update($this->validar($request));

        return redirect()->route('academico.liberacoes-frequencia.index')
            ->with('success', 'Liberação atualizada com sucesso.');
    }

    public function destroy(LiberacaoFrequencia $liberacoes_frequencium)
    {
        $liberacoes_frequencium->delete();

        return redirect()->route('academico.liberacoes-frequencia.index')
            ->with('success', 'Liberação removida.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'turma_montada_id' => 'required|exists:turmas_montadas,id',
            'profissional_id' => 'nullable|exists:profissionais,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);
    }

    private function dados(?LiberacaoFrequencia $registro): array
    {
        return [
            'registro' => $registro,
            'turmasMontadas' => TurmaMontada::with('turma')->orderByDesc('id')->get(),
            'profissionais' => Profissional::with('pessoa')->get(),
        ];
    }
}
