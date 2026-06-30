<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Disciplina;
use App\Models\ExameNivel;
use Illuminate\Http\Request;

class ExameNivelController extends Controller
{
    public function index()
    {
        $registros = ExameNivel::with('aluno.pessoa', 'disciplina')->orderByDesc('id')->paginate(20);

        return view('academico.exames-nivel.index', compact('registros'));
    }

    public function create()
    {
        return view('academico.exames-nivel.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        ExameNivel::create($this->validar($request));

        return redirect()->route('academico.exames-nivel.index')->with('success', 'Exame de nível registrado.');
    }

    public function edit(ExameNivel $exames_nivel)
    {
        return view('academico.exames-nivel.form', $this->dados($exames_nivel));
    }

    public function update(Request $request, ExameNivel $exames_nivel)
    {
        $exames_nivel->update($this->validar($request));

        return redirect()->route('academico.exames-nivel.index')->with('success', 'Exame atualizado.');
    }

    public function destroy(ExameNivel $exames_nivel)
    {
        $exames_nivel->delete();

        return redirect()->route('academico.exames-nivel.index')->with('success', 'Exame removido.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'nota' => 'nullable|numeric|min:0',
            'situacao' => 'required|in:' . implode(',', ExameNivel::SITUACOES),
            'data_exame' => 'nullable|date',
        ]);
    }

    private function dados(?ExameNivel $registro): array
    {
        return [
            'registro' => $registro,
            'alunos' => Aluno::with('pessoa')->get(),
            'disciplinas' => Disciplina::orderBy('nome')->get(),
        ];
    }
}
