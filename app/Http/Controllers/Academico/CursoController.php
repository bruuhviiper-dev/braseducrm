<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\AreaConhecimento;
use App\Models\Grau;
use App\Models\Habilitacao;
use App\Models\InstituicaoEnsino;
use App\Models\ModeloDocumento;
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
        return view('academico.cursos.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request, null);
        Curso::create($data);

        return redirect()->route('academico.cursos.index')
            ->with('success', 'Curso cadastrado com sucesso.');
    }

    public function edit(Curso $curso)
    {
        return view('academico.cursos.form', $this->dados($curso));
    }

    public function update(Request $request, Curso $curso)
    {
        $curso->update($this->validar($request, $curso));

        return redirect()->route('academico.cursos.index')
            ->with('success', 'Curso atualizado com sucesso.');
    }

    private function validar(Request $request, ?Curso $curso): array
    {
        // EDUQ: SIGLA é a chave primária do curso — única, apenas letras, números e pontos
        $v = $request->validate([
            'nome' => 'required|string|max:255',
            'sigla' => [
                'required', 'string', 'max:20', 'regex:/^[A-Za-z0-9.]+$/',
                'unique:cursos,sigla' . ($curso ? ',' . $curso->id : ''),
            ],
            'area_conhecimento_id' => 'nullable|exists:areas_conhecimento,id',
            'grau_id' => 'nullable|exists:graus,id',
            'habilitacao_id' => 'nullable|exists:habilitacoes,id',
            'instituicao_ensino_id' => 'nullable|exists:instituicoes_ensino,id',
            'modelo_documento_id' => 'nullable|exists:modelos_documento,id',
            'carga_horaria_total' => 'nullable|integer|min:0',
            'duracao_meses' => 'nullable|integer|min:0',
            'valor_comissao' => 'nullable|numeric|min:0',
            'descricao' => 'nullable|string',
        ], [
            'sigla.regex' => 'A SIGLA deve conter apenas letras, números e pontos (ex.: PSI.2025.1), sem espaços ou caracteres especiais.',
            'sigla.unique' => 'Já existe um curso com esta SIGLA. A SIGLA é a chave única do curso e não pode se repetir.',
        ]);

        $v['ativo'] = $request->boolean('ativo');
        $v['bloquear_menores'] = $request->boolean('bloquear_menores');
        $v['nao_gerar_nf'] = $request->boolean('nao_gerar_nf');

        return $v;
    }

    private function dados(?Curso $curso): array
    {
        if ($curso) {
            $curso->loadCount('matriculas');
            $curso->load('matriculas.aluno.pessoa', 'matriculas.turma');
        }

        return [
            'curso' => $curso,
            'areas' => AreaConhecimento::orderBy('nome')->get(),
            'graus' => Grau::orderBy('nome')->get(),
            'habilitacoes' => Habilitacao::orderBy('nome')->get(),
            'instituicoes' => InstituicaoEnsino::orderBy('nome')->get(),
            'modelos' => ModeloDocumento::orderBy('nome')->get(),
        ];
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();

        return redirect()->route('academico.cursos.index')
            ->with('success', 'Curso removido com sucesso.');
    }
}
