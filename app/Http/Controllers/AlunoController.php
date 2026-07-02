<?php

namespace App\Http\Controllers;

use App\Models\Alergia;
use App\Models\Aluno;
use App\Models\FormaIngresso;
use App\Models\NecessidadeEspecial;
use App\Models\Pessoa;
use App\Models\Titularidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlunoController extends Controller
{
    public function index(Request $request)
    {
        $query = Aluno::with(['pessoa', 'formaIngresso']);

        if ($search = $request->get('search')) {
            $query->whereHas('pessoa', function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%");
            })->orWhere('ra', 'like', "%{$search}%");
        }

        $alunos = $query->orderByDesc('id')->paginate(15)->withQueryString();

        return view('alunos.index', compact('alunos'));
    }

    public function create()
    {
        return view('alunos.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        DB::transaction(function () use ($data) {
            $aluno = Aluno::create($data['aluno']);
            $this->salvarFilhos($aluno, $data);
        });

        return redirect()->route('alunos.index')->with('success', 'Aluno cadastrado com sucesso.');
    }

    public function edit(Aluno $aluno)
    {
        $aluno->load(['pessoa', 'responsaveis', 'formacoes', 'matriculas.turma']);

        return view('alunos.form', $this->dados($aluno));
    }

    public function update(Request $request, Aluno $aluno)
    {
        $data = $this->validar($request);
        DB::transaction(function () use ($aluno, $data) {
            $aluno->update($data['aluno']);
            $this->salvarFilhos($aluno, $data);
        });

        return redirect()->route('alunos.index')->with('success', 'Aluno atualizado com sucesso.');
    }

    public function destroy(Aluno $aluno)
    {
        $aluno->delete();

        return redirect()->route('alunos.index')->with('success', 'Aluno removido com sucesso.');
    }

    private function validar(Request $request): array
    {
        $v = $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'ra' => 'nullable|string|max:50',
            'forma_ingresso_id' => 'nullable|exists:formas_ingresso,id',
            'titularidade_id' => 'nullable|exists:titularidades,id',
            'data_ingresso' => 'nullable|date',
            'informacoes_adicionais' => 'nullable|string|max:2000',
            // saúde
            'tipo_sanguineo' => 'nullable|in:' . implode(',', Aluno::TIPOS_SANGUINEOS),
            'alergia_id' => 'nullable|exists:alergias,id',
            'necessidade_especial_id' => 'nullable|exists:necessidades_especiais,id',
            'observacoes_saude' => 'nullable|string|max:2000',
            // responsáveis
            'responsaveis' => 'nullable|array',
            'responsaveis.*.nome' => 'nullable|string|max:255',
            'responsaveis.*.parentesco' => 'nullable|string|max:100',
            'responsaveis.*.cpf' => 'nullable|string|max:20',
            'responsaveis.*.telefone' => 'nullable|string|max:30',
            'responsaveis.*.email' => 'nullable|email|max:255',
            // formações
            'formacoes' => 'nullable|array',
            'formacoes.*.nivel' => 'nullable|string|max:100',
            'formacoes.*.instituicao' => 'nullable|string|max:255',
            'formacoes.*.curso' => 'nullable|string|max:255',
            'formacoes.*.ano_conclusao' => 'nullable|integer|min:1900|max:2100',
        ]);

        return [
            'aluno' => [
                'pessoa_id' => $v['pessoa_id'],
                'ra' => $v['ra'] ?? null,
                'forma_ingresso_id' => $v['forma_ingresso_id'] ?? null,
                'titularidade_id' => $v['titularidade_id'] ?? null,
                'data_ingresso' => $v['data_ingresso'] ?? null,
                'informacoes_adicionais' => $v['informacoes_adicionais'] ?? null,
                'tipo_sanguineo' => $v['tipo_sanguineo'] ?? null,
                'alergia_id' => $v['alergia_id'] ?? null,
                'necessidade_especial_id' => $v['necessidade_especial_id'] ?? null,
                'observacoes_saude' => $v['observacoes_saude'] ?? null,
                'ativo' => $request->boolean('ativo'),
            ],
            'responsaveis' => collect($v['responsaveis'] ?? [])->filter(fn ($r) => !empty($r['nome']))->values()->all(),
            'formacoes' => collect($v['formacoes'] ?? [])->filter(fn ($f) => !empty($f['instituicao']) || !empty($f['curso']))->values()->all(),
        ];
    }

    private function salvarFilhos(Aluno $aluno, array $data): void
    {
        $aluno->responsaveis()->delete();
        foreach ($data['responsaveis'] as $i => $r) {
            $aluno->responsaveis()->create([
                'nome' => $r['nome'],
                'parentesco' => $r['parentesco'] ?? null,
                'cpf' => $r['cpf'] ?? null,
                'telefone' => $r['telefone'] ?? null,
                'email' => $r['email'] ?? null,
                'principal' => $i === 0,
            ]);
        }

        $aluno->formacoes()->delete();
        foreach ($data['formacoes'] as $f) {
            $aluno->formacoes()->create([
                'nivel' => $f['nivel'] ?? null,
                'instituicao' => $f['instituicao'] ?? null,
                'curso' => $f['curso'] ?? null,
                'ano_conclusao' => $f['ano_conclusao'] ?? null,
            ]);
        }
    }

    private function dados(?Aluno $aluno): array
    {
        return [
            'aluno' => $aluno,
            'pessoas' => Pessoa::orderBy('nome')->get(),
            'formasIngresso' => FormaIngresso::orderBy('nome')->get(),
            'titularidades' => Titularidade::orderBy('nome')->get(),
            'alergias' => Alergia::orderBy('nome')->get(),
            'necessidades' => NecessidadeEspecial::orderBy('nome')->get(),
        ];
    }
}
