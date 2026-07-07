<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\AreaConhecimento;
use App\Models\Curso;
use App\Models\Disciplina;
use App\Models\ConfiguracaoBoletim;
use App\Models\EstruturaPlano;
use App\Models\Grau;
use App\Models\Habilitacao;
use App\Models\MatrizCurricular;
use App\Models\Modulo;
use App\Models\TabelaAvaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatrizCurricularController extends Controller
{
    public function index()
    {
        $matrizes = MatrizCurricular::with('curso')->withCount('disciplinas')->paginate(15);

        return view('academico.matrizes.index', compact('matrizes'));
    }

    public function create()
    {
        return view('academico.matrizes.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        DB::transaction(function () use ($data) {
            $matriz = MatrizCurricular::create($data['matriz']);
            $this->salvarDisciplinas($matriz, $data['disciplinas']);
        });

        return redirect()->route('academico.matrizes.index')
            ->with('success', 'Matriz curricular cadastrada com sucesso.');
    }

    public function edit(MatrizCurricular $matrize)
    {
        return view('academico.matrizes.form', $this->dados($matrize));
    }

    public function update(Request $request, MatrizCurricular $matrize)
    {
        // EDUQ: alterar uma matriz com alunos vinculados exige a digitação de palavra-chave de confirmação
        if ($this->temAlunosVinculados($matrize) && strtoupper((string) $request->input('palavra_chave')) !== 'ALTERAR') {
            return back()->withInput()->withErrors([
                'palavra_chave' => 'Esta matriz possui alunos vinculados. Digite a palavra-chave ALTERAR para confirmar a modificação da grade.',
            ]);
        }

        $data = $this->validar($request);
        DB::transaction(function () use ($matrize, $data) {
            $matrize->update($data['matriz']);
            $this->salvarDisciplinas($matrize, $data['disciplinas']);
        });

        return redirect()->route('academico.matrizes.index')
            ->with('success', 'Matriz curricular atualizada com sucesso.');
    }

    public function destroy(MatrizCurricular $matrize)
    {
        if ($this->temAlunosVinculados($matrize)) {
            return redirect()->route('academico.matrizes.index')
                ->with('error', 'Esta matriz curricular possui alunos vinculados através de turmas e não pode ser removida.');
        }

        $matrize->delete();

        return redirect()->route('academico.matrizes.index')
            ->with('success', 'Matriz curricular removida com sucesso.');
    }

    private function validar(Request $request): array
    {
        $v = $request->validate([
            'nome' => 'required|string|max:255',
            'sigla' => 'nullable|string|max:60',
            'curso_id' => 'required|exists:cursos,id',
            'area_conhecimento_id' => 'nullable|exists:areas_conhecimento,id',
            'grau_id' => 'nullable|exists:graus,id',
            'habilitacao_id' => 'nullable|exists:habilitacoes,id',
            'configuracao_boletim_id' => 'nullable|integer',
            'codigo_emec' => 'nullable|string|max:255',
            'ato_autorizacao' => 'nullable|string|max:255',
            'ato_reconhecimento' => 'nullable|string|max:255',
            'ato_renovacao' => 'nullable|string|max:255',
            'tabela_avaliacao_id' => 'nullable|integer',
            'estrutura_plano_aula_id' => 'nullable|integer',
            'estrutura_plano_ensino_id' => 'nullable|integer',
            'carga_horaria_descritiva' => 'nullable|string|max:120',
            'inicio_vigencia' => 'nullable|date',
            'observacoes' => 'nullable|string',
            'anotacoes' => 'nullable|string',
            'sistema_curricular' => 'nullable|string|max:60',
            'percentual_frequencia' => 'nullable|integer|min:0|max:100',
            'horas_compl' => 'nullable|integer|min:0',
            'horas_compl_min' => 'nullable|integer|min:0',
            // disciplinas por módulo
            'disciplinas' => 'nullable|array',
            'disciplinas.*.disciplina_id' => 'nullable|exists:disciplinas,id',
            'disciplinas.*.modulo_id' => 'nullable|exists:modulos,id',
            'disciplinas.*.carga_horaria' => 'nullable|integer|min:0',
            'disciplinas.*.creditos' => 'nullable|integer|min:0',
            'disciplinas.*.tipo_vinculo' => 'nullable|in:obrigatoria,optativa,nao_obrigatoria',
            'disciplinas.*.ead' => 'nullable',
        ]);

        return [
            'matriz' => [
                'nome' => $v['nome'],
                'sigla' => $v['sigla'] ?? null,
                'curso_id' => $v['curso_id'],
                'area_conhecimento_id' => $v['area_conhecimento_id'] ?? null,
                'grau_id' => $v['grau_id'] ?? null,
                'habilitacao_id' => $v['habilitacao_id'] ?? null,
                'configuracao_boletim_id' => $v['configuracao_boletim_id'] ?? null,
                'tabela_avaliacao_id' => $v['tabela_avaliacao_id'] ?? null,
                'estrutura_plano_aula_id' => $v['estrutura_plano_aula_id'] ?? null,
                'estrutura_plano_ensino_id' => $v['estrutura_plano_ensino_id'] ?? null,
                'carga_horaria_descritiva' => $v['carga_horaria_descritiva'] ?? null,
                // EDUQ usa apenas o toggle "Ativo" (sem campo Situação); derivamos p/ compat do schema
                'situacao' => $request->boolean('ativo') ? 'ativa' : 'finalizada',
                'ativo' => $request->boolean('ativo'),
                'inicio_vigencia' => $v['inicio_vigencia'] ?? null,
                'observacoes' => $v['observacoes'] ?? null,
                'anotacoes' => $v['anotacoes'] ?? null,
                'matricular_todas' => $request->boolean('matricular_todas'),
                'permite_duplicadas' => $request->boolean('permite_duplicadas'),
                'percentual_frequencia' => $v['percentual_frequencia'] ?? null,
                'sistema_curricular' => $v['sistema_curricular'] ?? null,
                'controla_horas_compl' => $request->boolean('controla_horas_compl'),
                'horas_compl' => $v['horas_compl'] ?? null,
                'horas_compl_min' => $v['horas_compl_min'] ?? null,
                'controla_extensao' => $request->boolean('controla_extensao'),
                'controla_estagio' => $request->boolean('controla_estagio'),
                'historico_parcial_portal' => $request->boolean('historico_parcial_portal'),
            ],
            'disciplinas' => collect($v['disciplinas'] ?? [])
                ->filter(fn ($d) => !empty($d['disciplina_id']))
                ->values()->all(),
        ];
    }

    /** EDUQ: matriz vinculada a turma com alunos não pode ser alterada livremente. */
    private function temAlunosVinculados(MatrizCurricular $matriz): bool
    {
        return DB::table('matriculas')
            ->whereIn('turma_id', DB::table('turmas')->where('matriz_curricular_id', $matriz->id)->pluck('id'))
            ->exists();
    }

    private function salvarDisciplinas(MatrizCurricular $matriz, array $disciplinas): void
    {
        $sync = [];
        foreach ($disciplinas as $i => $d) {
            // belongsToMany sync com pivô por chave disciplina_id; usar syncWithoutDetaching não serve
            // pois pode haver a mesma disciplina em módulos diferentes -> montamos array numérico
            $tipo = $d['tipo_vinculo'] ?? 'obrigatoria';
            $sync[] = [
                'disciplina_id' => $d['disciplina_id'],
                'modulo_id' => !empty($d['modulo_id']) ? $d['modulo_id'] : null,
                'carga_horaria' => $d['carga_horaria'] !== '' ? ($d['carga_horaria'] ?? null) : null,
                'creditos' => $d['creditos'] !== '' ? ($d['creditos'] ?? null) : null,
                'ordem' => $i,
                'tipo_vinculo' => $tipo,
                'obrigatoria' => $tipo === 'obrigatoria',
                'ead' => !empty($d['ead']),
            ];
        }
        // rebuild pivô manualmente (permite disciplina repetida em módulos diferentes)
        DB::table('matriz_disciplinas')->where('matriz_curricular_id', $matriz->id)->delete();
        $now = now();
        DB::table('matriz_disciplinas')->insert(array_map(fn ($r) => array_merge($r, [
            'matriz_curricular_id' => $matriz->id,
            'created_at' => $now,
            'updated_at' => $now,
        ]), $sync));
    }

    private function dados(?MatrizCurricular $matriz): array
    {
        $disciplinasSel = [];
        if ($matriz) {
            $disciplinasSel = DB::table('matriz_disciplinas')
                ->where('matriz_curricular_id', $matriz->id)
                ->orderBy('ordem')
                ->get(['disciplina_id', 'modulo_id', 'carga_horaria', 'creditos', 'tipo_vinculo', 'ead'])
                ->map(fn ($d) => [
                    'disciplina_id' => $d->disciplina_id,
                    'modulo_id' => $d->modulo_id,
                    'carga_horaria' => $d->carga_horaria,
                    'creditos' => $d->creditos,
                    'tipo_vinculo' => $d->tipo_vinculo ?: 'obrigatoria',
                    'ead' => (bool) $d->ead,
                ])->all();
        }

        return [
            'matriz' => $matriz,
            'temAlunos' => $matriz ? $this->temAlunosVinculados($matriz) : false,
            'disciplinasSel' => $disciplinasSel,
            'cursos' => Curso::orderBy('nome')->get(),
            'areas' => AreaConhecimento::orderBy('nome')->get(),
            'graus' => Grau::orderBy('nome')->get(),
            'habilitacoes' => Habilitacao::orderBy('nome')->get(),
            'configBoletins' => ConfiguracaoBoletim::orderBy('nome')->get(),
            'tabelasAvaliacao' => TabelaAvaliacao::orderBy('nome')->get(),
            'estruturasPlano' => EstruturaPlano::orderBy('nome')->get(),
            'modulos' => Modulo::orderBy('nome')->get(),
            'disciplinas' => Disciplina::where('ativo', true)->orderBy('nome')->get(),
        ];
    }
}
