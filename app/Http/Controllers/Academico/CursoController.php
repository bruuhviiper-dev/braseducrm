<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\AreaConhecimento;
use App\Models\Grau;
use App\Models\Habilitacao;
use App\Models\InstituicaoEnsino;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::with(['areaConhecimento', 'grau'])->paginate(15);

        return view('academico.cursos.index', compact('cursos'));
    }

    public function create()
    {
        $areas = AreaConhecimento::orderBy('nome')->get();
        $graus = Grau::orderBy('nome')->get();
        $habilitacoes = Habilitacao::orderBy('nome')->get();
        $instituicoes = InstituicaoEnsino::orderBy('nome')->get();

        return view('academico.cursos.form', compact('areas', 'graus', 'habilitacoes', 'instituicoes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'sigla' => 'required|string|max:20',
            'area_conhecimento_id' => 'nullable|exists:areas_conhecimento,id',
            'grau_id' => 'nullable|exists:graus,id',
            'habilitacao_id' => 'nullable|exists:habilitacoes,id',
            'instituicao_ensino_id' => 'nullable|exists:instituicoes_ensino,id',
            'carga_horaria_total' => 'nullable|integer|min:0',
            'duracao_meses' => 'nullable|integer|min:0',
            'descricao' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['ativo'] = $request->has('ativo');

        Curso::create($data);

        return redirect()->route('academico.cursos.index')
            ->with('success', 'Curso cadastrado com sucesso.');
    }

    public function edit(Curso $curso)
    {
        $areas = AreaConhecimento::orderBy('nome')->get();
        $graus = Grau::orderBy('nome')->get();
        $habilitacoes = Habilitacao::orderBy('nome')->get();
        $instituicoes = InstituicaoEnsino::orderBy('nome')->get();

        return view('academico.cursos.form', compact('curso', 'areas', 'graus', 'habilitacoes', 'instituicoes'));
    }

    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'sigla' => 'required|string|max:20',
            'area_conhecimento_id' => 'nullable|exists:areas_conhecimento,id',
            'grau_id' => 'nullable|exists:graus,id',
            'habilitacao_id' => 'nullable|exists:habilitacoes,id',
            'instituicao_ensino_id' => 'nullable|exists:instituicoes_ensino,id',
            'carga_horaria_total' => 'nullable|integer|min:0',
            'duracao_meses' => 'nullable|integer|min:0',
            'descricao' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['ativo'] = $request->has('ativo');

        $curso->update($data);

        return redirect()->route('academico.cursos.index')
            ->with('success', 'Curso atualizado com sucesso.');
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();

        return redirect()->route('academico.cursos.index')
            ->with('success', 'Curso removido com sucesso.');
    }
}
