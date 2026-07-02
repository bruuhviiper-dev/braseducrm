<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\CategoriaInteressado;
use App\Models\Curso;
use App\Models\Interessado;
use App\Models\OrigemInteressado;
use App\Models\Pessoa;
use App\Models\Profissao;
use App\Models\User;
use App\Services\RdStationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InteressadoController extends Controller
{
    public function index()
    {
        $interessados = Interessado::with(['pessoa', 'origemInteressado', 'categoriaInteressado', 'curso'])
            ->orderBy('id', 'desc')->paginate(15);

        return view('crm.interessados.index', compact('interessados'));
    }

    public function create()
    {
        return view('crm.interessados.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $interessado = DB::transaction(function () use ($data) {
            $data['interessado']['ativo'] = true;
            $i = Interessado::create($data['interessado']);
            $this->salvarContatos($i, $data['contatos']);
            return $i;
        });

        (new RdStationService())->enviarLead($interessado);

        return redirect()->route('crm.interessados.index')->with('success', 'Interessado cadastrado com sucesso.');
    }

    public function edit(Interessado $interessado)
    {
        $interessado->load('contatos');

        return view('crm.interessados.form', $this->dados($interessado));
    }

    public function update(Request $request, Interessado $interessado)
    {
        $data = $this->validar($request);
        DB::transaction(function () use ($interessado, $data, $request) {
            $data['interessado']['ativo'] = $request->boolean('ativo');
            $interessado->update($data['interessado']);
            $this->salvarContatos($interessado, $data['contatos']);
        });

        return redirect()->route('crm.interessados.index')->with('success', 'Interessado atualizado com sucesso.');
    }

    public function destroy(Interessado $interessado)
    {
        $interessado->delete();

        return redirect()->route('crm.interessados.index')->with('success', 'Interessado removido com sucesso.');
    }

    private function validar(Request $request): array
    {
        $v = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'cpf' => 'nullable|string|max:20',
            'telefone' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
            'origem_id' => 'nullable|exists:origens_interessado,id',
            'responsavel_id' => 'nullable|exists:users,id',
            'categoria_id' => 'nullable|exists:categorias_interessado,id',
            'profissao_id' => 'nullable|exists:profissoes,id',
            'pessoa_id' => 'nullable|exists:pessoas,id',
            'curso_id' => 'nullable|exists:cursos,id',
            'cidade' => 'nullable|string|max:120',
            'formacao' => 'nullable|string|max:120',
            'instagram' => 'nullable|string|max:120',
            'facebook' => 'nullable|string|max:120',
            'observacoes' => 'nullable|string',
            'contatos' => 'nullable|array',
            'contatos.*.nome' => 'nullable|string|max:120',
            'contatos.*.telefone' => 'nullable|string|max:30',
            'contatos.*.email' => 'nullable|string|max:120',
        ]);

        return [
            'interessado' => [
                'nome' => $v['nome'],
                'e_empresa' => $request->boolean('e_empresa'),
                'nao_enviar_mensagens' => $request->boolean('nao_enviar_mensagens'),
                'email' => $v['email'] ?? null,
                'cpf' => $v['cpf'] ?? null,
                'telefone' => $v['telefone'] ?? null,
                'celular' => $v['celular'] ?? null,
                'origem_id' => $v['origem_id'] ?? null,
                'responsavel_id' => $v['responsavel_id'] ?? null,
                'categoria_id' => $v['categoria_id'] ?? null,
                'profissao_id' => $v['profissao_id'] ?? null,
                'pessoa_id' => $v['pessoa_id'] ?? null,
                'curso_id' => $v['curso_id'] ?? null,
                'cidade' => $v['cidade'] ?? null,
                'formacao' => $v['formacao'] ?? null,
                'instagram' => $v['instagram'] ?? null,
                'facebook' => $v['facebook'] ?? null,
                'observacoes' => $v['observacoes'] ?? null,
            ],
            'contatos' => collect($v['contatos'] ?? [])->filter(fn ($c) => !empty($c['nome']) || !empty($c['telefone']))->values()->all(),
        ];
    }

    private function salvarContatos(Interessado $interessado, array $contatos): void
    {
        $interessado->contatos()->delete();
        foreach ($contatos as $c) {
            $interessado->contatos()->create([
                'nome' => $c['nome'] ?? null,
                'telefone' => $c['telefone'] ?? null,
                'email' => $c['email'] ?? null,
            ]);
        }
    }

    private function dados(?Interessado $interessado): array
    {
        return [
            'interessado' => $interessado,
            'origens' => OrigemInteressado::where('ativo', true)->orderBy('nome')->get(),
            'categorias' => CategoriaInteressado::orderBy('nome')->get(),
            'cursos' => Curso::where('ativo', true)->orderBy('nome')->get(),
            'consultores' => User::where('ativo', true)->orderBy('nome')->get(),
            'profissoes' => Profissao::orderBy('nome')->get(),
            'pessoas' => Pessoa::orderBy('nome')->get(),
        ];
    }
}
