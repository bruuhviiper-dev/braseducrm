<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Models\Matricula;
use App\Models\PraticaSupervisionada;
use Illuminate\Http\Request;

class PraticaSupervisionadaController extends Controller
{
    public function index()
    {
        $registros = PraticaSupervisionada::with('matricula.aluno.pessoa', 'disciplina')->orderByDesc('id')->paginate(20);

        return view('academico.praticas-supervisionadas.index', compact('registros'));
    }

    public function create()
    {
        return view('academico.praticas-supervisionadas.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        PraticaSupervisionada::create($this->validar($request));

        return redirect()->route('academico.praticas-supervisionadas.index')
            ->with('success', 'Prática supervisionada lançada com sucesso.');
    }

    public function edit(PraticaSupervisionada $praticas_supervisionada)
    {
        return view('academico.praticas-supervisionadas.form', $this->dados($praticas_supervisionada));
    }

    public function update(Request $request, PraticaSupervisionada $praticas_supervisionada)
    {
        $praticas_supervisionada->update($this->validar($request));

        return redirect()->route('academico.praticas-supervisionadas.index')
            ->with('success', 'Registro atualizado com sucesso.');
    }

    public function destroy(PraticaSupervisionada $praticas_supervisionada)
    {
        $praticas_supervisionada->delete();

        return redirect()->route('academico.praticas-supervisionadas.index')
            ->with('success', 'Registro removido.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'matricula_id' => 'required|exists:matriculas,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'quantidade' => 'required|numeric|min:0',
            'situacao' => 'required|in:' . implode(',', PraticaSupervisionada::SITUACOES),
        ]);
    }

    private function dados(?PraticaSupervisionada $registro): array
    {
        return [
            'registro' => $registro,
            'matriculas' => Matricula::with('aluno.pessoa')->orderByDesc('id')->get(),
            'disciplinas' => Disciplina::orderBy('nome')->get(),
        ];
    }
}
