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
            // documentos civis (doc aba 2)
            'rg_uf' => 'nullable|string|max:2',
            'rg_data_expedicao' => 'nullable|date',
            'certidao_matricula' => 'nullable|string|max:60',
            'certidao_numero' => 'nullable|string|max:30',
            'certidao_folha' => 'nullable|string|max:20',
            'certidao_livro' => 'nullable|string|max:20',
            'reservista' => 'nullable|string|max:60',
            'titulo_eleitor' => 'nullable|string|max:40',
            'titulo_zona' => 'nullable|string|max:10',
            'titulo_municipio' => 'nullable|string|max:120',
            'titulo_data_expedicao' => 'nullable|date',
            // dados para contas a pagar
            'forma_pagamento_padrao' => 'nullable|string|max:20',
            'dia_pagamento' => 'nullable|integer|min:1|max:31',
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
                'rg_uf' => $v['rg_uf'] ?? null,
                'rg_data_expedicao' => $v['rg_data_expedicao'] ?? null,
                'certidao_matricula' => $v['certidao_matricula'] ?? null,
                'certidao_numero' => $v['certidao_numero'] ?? null,
                'certidao_folha' => $v['certidao_folha'] ?? null,
                'certidao_livro' => $v['certidao_livro'] ?? null,
                'reservista' => $v['reservista'] ?? null,
                'titulo_eleitor' => $v['titulo_eleitor'] ?? null,
                'titulo_zona' => $v['titulo_zona'] ?? null,
                'titulo_municipio' => $v['titulo_municipio'] ?? null,
                'titulo_data_expedicao' => $v['titulo_data_expedicao'] ?? null,
                'forma_pagamento_padrao' => $v['forma_pagamento_padrao'] ?? null,
                'dia_pagamento' => $v['dia_pagamento'] ?? null,
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

    /** Aba Contas / PIX: reembolsar/pagar a pessoa. */
    public function adicionarConta(Request $request, Pessoa $pessoa)
    {
        $v = $request->validate([
            'tipo' => 'required|in:pix,bancaria',
            'chave_pix_tipo' => 'nullable|string|max:30',
            'chave_pix' => 'nullable|string|max:255',
            'banco' => 'nullable|string|max:10',
            'agencia' => 'nullable|string|max:20',
            'conta' => 'nullable|string|max:30',
            'tipo_conta' => 'nullable|string|max:20',
            'nome_titular' => 'nullable|string|max:255',
            'cpf_titular' => 'nullable|string|max:18',
        ]);
        \App\Models\ContaPessoa::create($v + [
            'pessoa_id' => $pessoa->id,
            'do_titular' => $request->boolean('do_titular'),
        ]);

        return back()->with('success', 'Conta / PIX adicionada.');
    }

    public function removerConta(Pessoa $pessoa, \App\Models\ContaPessoa $conta)
    {
        abort_unless($conta->pessoa_id === $pessoa->id, 404);
        $conta->delete();

        return back()->with('success', 'Conta removida.');
    }

    /** Aba Anexos: upload com fluxo de homologação (em_analise → aprovado/rejeitado). */
    public function uploadAnexo(Request $request, Pessoa $pessoa)
    {
        $v = $request->validate([
            'tipo_documento' => 'required|string|max:100',
            'arquivo' => 'required|file|max:20480',
            'descricao' => 'nullable|string|max:500',
        ]);
        $caminho = $request->file('arquivo')->store('pessoas/anexos', 'public');
        \App\Models\AnexoPessoa::create([
            'pessoa_id' => $pessoa->id,
            'tipo_documento' => $v['tipo_documento'],
            'arquivo' => $caminho,
            'descricao' => $v['descricao'] ?? null,
            'user_id' => auth()->id(),
            'situacao' => 'em_analise',
        ]);

        return back()->with('success', 'Documento enviado para análise.');
    }

    public function aprovacaoAnexo(Request $request, Pessoa $pessoa, \App\Models\AnexoPessoa $anexo)
    {
        abort_unless($anexo->pessoa_id === $pessoa->id, 404);
        $decisao = $request->input('decisao'); // aprovar|rejeitar
        $anexo->update([
            'situacao' => $decisao === 'aprovar' ? 'aprovado' : 'rejeitado',
            'motivo_rejeicao' => $decisao !== 'aprovar' ? $request->input('motivo', 'Documento inválido') : null,
        ]);

        return back()->with('success', $decisao === 'aprovar' ? 'Documento aprovado.' : 'Documento rejeitado.');
    }

    private function dados(?Pessoa $pessoa): array
    {
        $contas = $pessoa ? \App\Models\ContaPessoa::where('pessoa_id', $pessoa->id)->get() : collect();
        $anexos = $pessoa ? \App\Models\AnexoPessoa::where('pessoa_id', $pessoa->id)->with('user')->orderByDesc('id')->get() : collect();

        return [
            'pessoa' => $pessoa,
            'religioes' => Religiao::orderBy('nome')->get(),
            'profissoes' => Profissao::orderBy('nome')->get(),
            'escolas' => Escola::orderBy('nome')->get(),
            'alergias' => Alergia::orderBy('nome')->get(),
            'necessidades' => NecessidadeEspecial::orderBy('nome')->get(),
            'contas' => $contas,
            'anexos' => $anexos,
        ];
    }
}
