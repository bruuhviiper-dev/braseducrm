<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Models\EstruturaPlano;
use App\Models\Horario;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    public function index()
    {
        $disciplinas = Disciplina::paginate(15);

        return view('academico.disciplinas.index', compact('disciplinas'));
    }

    public function create()
    {
        return view('academico.disciplinas.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        Disciplina::create($this->validar($request));

        return redirect()->route('academico.disciplinas.index')
            ->with('success', 'Disciplina cadastrada com sucesso.');
    }

    public function edit(Disciplina $disciplina)
    {
        return view('academico.disciplinas.form', $this->dados($disciplina));
    }

    public function update(Request $request, Disciplina $disciplina)
    {
        $disciplina->update($this->validar($request));

        return redirect()->route('academico.disciplinas.index')
            ->with('success', 'Disciplina atualizada com sucesso.');
    }

    public function destroy(Disciplina $disciplina)
    {
        $disciplina->delete();

        return redirect()->route('academico.disciplinas.index')
            ->with('success', 'Disciplina removida com sucesso.');
    }

    private function validar(Request $request): array
    {
        $v = $request->validate([
            'nome' => 'required|string|max:255',
            'sigla' => 'required|string|max:20',
            'estrutura_plano_ensino_id' => 'nullable|integer',
        ]);

        $v['ativo'] = $request->boolean('ativo');

        return $v;
    }

    private function dados(?Disciplina $disciplina): array
    {
        $matrizes = collect();
        $aulas = collect();

        if ($disciplina) {
            // Matrizes Curriculares que incluem a disciplina
            $matrizes = $disciplina->matrizes()->with('curso')->get();
            // Detalhes de Aula: professores que ministraram aula (agrupados por turma montada)
            $aulas = Horario::where('disciplina_id', $disciplina->id)
                ->with(['turmaMontada.turma', 'profissional.pessoa'])
                ->get();
        }

        return [
            'disciplina' => $disciplina,
            'estruturasPlano' => EstruturaPlano::orderBy('nome')->get(),
            'matrizes' => $matrizes,
            'aulas' => $aulas,
        ];
    }
}
