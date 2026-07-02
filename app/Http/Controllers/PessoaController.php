<?php

namespace App\Http\Controllers;

use App\Models\Alergia;
use App\Models\Escola;
use App\Models\NecessidadeEspecial;
use App\Models\Pessoa;
use App\Models\Profissao;
use App\Models\Religiao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PessoaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pessoa::with(['aluno', 'profissional']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pessoas = $query->orderBy('nome')->paginate(15)->withQueryString();

        return view('pessoas.index', compact('pessoas'));
    }

    public function create()
    {
        return view('pessoas.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        DB::transaction(function () use ($data) {
            $pessoa = Pessoa::create($data['pessoa']);
            $this->salvarFilhos($pessoa, $data);
        });

        return redirect()->route('pessoas.index')->with('success', 'Pessoa cadastrada com sucesso.');
    }

    public function show(Pessoa $pessoa)
    {
        $pessoa->load(['religiao', 'profissao', 'escola', 'aluno.formaIngresso', 'profissional']);

        return view('pessoas.show', compact('pessoa'));
    }

    public function edit(Pessoa $pessoa)
    {
        $pessoa->load(['telefones', 'contas', 'alergias', 'necessidadesEspeciais', 'aluno.matriculas.turma']);

        return view('pessoas.form', $this->dados($pessoa));
    }

    public function update(Request $request, Pessoa $pessoa)
    {
        $data = $this->validar($request, $pessoa);
        DB::transaction(function () use ($pessoa, $data) {
            $pessoa->update($data['pessoa']);
            $this->salvarFilhos($pessoa, $data);
        });

        return redirect()->route('pessoas.index')->with('success', 'Pessoa atualizada com sucesso.');
    }

    public function destroy(Pessoa $pessoa)
    {
        $pessoa->delete();

        return redirect()->route('pessoas.index')->with('success', 'Pessoa removida com sucesso.');
    }

    private function validar(Request $request, ?Pessoa $pessoa = null): array
    {
        $cpfRule = 'nullable|string|max:14|unique:pessoas,cpf' . ($pessoa ? ',' . $pessoa->id : '');

        $v = $request->validate([
            'tipo' => 'required|in:fisica,juridica',
            'estrangeiro' => 'nullable',
            'nome' => 'required|string|max:255',
            'nome_social' => 'nullable|string|max:255',
            'cpf' => $cpfRule,
            'cnpj' => 'nullable|string|max:18',
            'rg' => 'nullable|string|max:30',
            'orgao_emissor' => 'nullable|string|max:30',
            'passaporte' => 'nullable|string|max:30',
            'data_nascimento' => 'nullable|date',
            'sexo' => 'nullable|string|max:10',
            'estado_civil' => 'nullable|string|max:30',
            'etnia' => 'nullable|string|max:30',
            'nacionalidade' => 'nullable|string|max:60',
            'naturalidade' => 'nullable|string|max:120',
            'origem' => 'nullable|string|max:120',
            'nome_pai' => 'nullable|string|max:255',
            'nome_mae' => 'nullable|string|max:255',
            // endereço
            'cep' => 'nullable|string|max:10',
            'endereco' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'complemento' => 'nullable|string|max:120',
            'bairro' => 'nullable|string|max:120',
            'caixa_postal' => 'nullable|string|max:30',
            'cidade' => 'nullable|string|max:120',
            'uf' => 'nullable|string|max:2',
            'pais' => 'nullable|string|max:60',
            // contato
            'email' => 'nullable|email|max:255',
            'email_secundario' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:30',
            'celular' => 'nullable|string|max:30',
            'instagram' => 'nullable|string|max:120',
            'facebook' => 'nullable|string|max:120',
            'linkedin' => 'nullable|string|max:120',
            // profissão
            'religiao_id' => 'nullable|exists:religioes,id',
            'profissao_id' => 'nullable|exists:profissoes,id',
            'local_trabalho' => 'nullable|string|max:255',
            'numero_conselho' => 'nullable|string|max:60',
            'lattes' => 'nullable|string|max:120',
            'escola_id' => 'nullable|exists:escolas,id',
            // infos adicionais
            'observacoes' => 'nullable|string',
            'observacoes_saude' => 'nullable|string',
            // saúde (multi)
            'alergias' => 'nullable|array',
            'alergias.*' => 'integer|exists:alergias,id',
            'necessidades' => 'nullable|array',
            'necessidades.*' => 'integer|exists:necessidades_especiais,id',
            // telefones (repeater)
            'telefones' => 'nullable|array',
            'telefones.*.numero' => 'nullable|string|max:30',
            'telefones.*.tipo' => 'nullable|string|max:30',
            'telefones.*.whatsapp' => 'nullable',
            'telefones.*.observacao' => 'nullable|string|max:120',
            // contas / pix (repeater)
            'contas' => 'nullable|array',
            'contas.*.banco' => 'nullable|string|max:60',
            'contas.*.agencia' => 'nullable|string|max:20',
            'contas.*.conta' => 'nullable|string|max:30',
            'contas.*.tipo' => 'nullable|string|max:20',
            'contas.*.chave_pix' => 'nullable|string|max:120',
            'contas.*.tipo_pix' => 'nullable|string|max:20',
        ]);

        return [
            'pessoa' => [
                'tipo' => $v['tipo'],
                'estrangeiro' => $request->boolean('estrangeiro'),
                'nome' => $v['nome'],
                'nome_social' => $v['nome_social'] ?? null,
                'cpf' => $v['cpf'] ?? null,
                'cnpj' => $v['cnpj'] ?? null,
                'rg' => $v['rg'] ?? null,
                'orgao_emissor' => $v['orgao_emissor'] ?? null,
                'passaporte' => $v['passaporte'] ?? null,
                'data_nascimento' => $v['data_nascimento'] ?? null,
                'sexo' => $v['sexo'] ?? null,
                'estado_civil' => $v['estado_civil'] ?? null,
                'etnia' => $v['etnia'] ?? null,
                'nacionalidade' => $v['nacionalidade'] ?? null,
                'naturalidade' => $v['naturalidade'] ?? null,
                'origem' => $v['origem'] ?? null,
                'nome_pai' => $v['nome_pai'] ?? null,
                'nome_mae' => $v['nome_mae'] ?? null,
                'cep' => $v['cep'] ?? null,
                'endereco' => $v['endereco'] ?? null,
                'numero' => $v['numero'] ?? null,
                'complemento' => $v['complemento'] ?? null,
                'bairro' => $v['bairro'] ?? null,
                'caixa_postal' => $v['caixa_postal'] ?? null,
                'cidade' => $v['cidade'] ?? null,
                'uf' => $v['uf'] ?? null,
                'pais' => $v['pais'] ?? 'Brasil',
                'email' => $v['email'] ?? null,
                'email_secundario' => $v['email_secundario'] ?? null,
                'telefone' => $v['telefone'] ?? null,
                'celular' => $v['celular'] ?? null,
                'instagram' => $v['instagram'] ?? null,
                'facebook' => $v['facebook'] ?? null,
                'linkedin' => $v['linkedin'] ?? null,
                'religiao_id' => $v['religiao_id'] ?? null,
                'profissao_id' => $v['profissao_id'] ?? null,
                'local_trabalho' => $v['local_trabalho'] ?? null,
                'numero_conselho' => $v['numero_conselho'] ?? null,
                'lattes' => $v['lattes'] ?? null,
                'escola_id' => $v['escola_id'] ?? null,
                'observacoes' => $v['observacoes'] ?? null,
                'observacoes_saude' => $v['observacoes_saude'] ?? null,
                'nao_receber_mensagens' => $request->boolean('nao_receber_mensagens'),
                'blacklist' => $request->boolean('blacklist'),
                'ignorar_reajuste' => $request->boolean('ignorar_reajuste'),
                'ativo' => $request->boolean('ativo'),
            ],
            'alergias' => array_map('intval', $v['alergias'] ?? []),
            'necessidades' => array_map('intval', $v['necessidades'] ?? []),
            'telefones' => collect($v['telefones'] ?? [])->filter(fn ($t) => !empty($t['numero']))->values()->all(),
            'contas' => collect($v['contas'] ?? [])->filter(fn ($c) => !empty($c['banco']) || !empty($c['chave_pix']))->values()->all(),
        ];
    }

    private function salvarFilhos(Pessoa $pessoa, array $data): void
    {
        $pessoa->alergias()->sync($data['alergias']);
        $pessoa->necessidadesEspeciais()->sync($data['necessidades']);

        $pessoa->telefones()->delete();
        foreach ($data['telefones'] as $t) {
            $pessoa->telefones()->create([
                'tipo' => $t['tipo'] ?? null,
                'numero' => $t['numero'],
                'whatsapp' => !empty($t['whatsapp']),
                'observacao' => $t['observacao'] ?? null,
            ]);
        }

        $pessoa->contas()->delete();
        foreach ($data['contas'] as $c) {
            $pessoa->contas()->create([
                'banco' => $c['banco'] ?? null,
                'agencia' => $c['agencia'] ?? null,
                'conta' => $c['conta'] ?? null,
                'tipo' => $c['tipo'] ?? null,
                'chave_pix' => $c['chave_pix'] ?? null,
                'tipo_pix' => $c['tipo_pix'] ?? null,
                'titular' => $c['titular'] ?? null,
            ]);
        }
    }

    private function dados(?Pessoa $pessoa): array
    {
        return [
            'pessoa' => $pessoa,
            'religioes' => Religiao::orderBy('nome')->get(),
            'profissoes' => Profissao::orderBy('nome')->get(),
            'escolas' => Escola::orderBy('nome')->get(),
            'alergias' => Alergia::orderBy('nome')->get(),
            'necessidades' => NecessidadeEspecial::orderBy('nome')->get(),
        ];
    }
}
