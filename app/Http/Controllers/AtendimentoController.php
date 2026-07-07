<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\CategoriaAtendimento;
use App\Models\Pessoa;
use Illuminate\Http\Request;

class AtendimentoController extends Controller
{
    public function index()
    {
        $atendimentos = Atendimento::with(['pessoa', 'categoria', 'operador'])
            ->orderBy('id', 'desc')->paginate(20);
        return view('administrativo.atendimentos.index', compact('atendimentos'));
    }

    public function create()
    {
        $pessoas = Pessoa::orderBy('nome')->get();
        $categorias = CategoriaAtendimento::orderBy('nome')->get();
        $responsaveis = \App\Models\User::where('ativo', true)->orderBy('nome')->get();
        $motivosFalha = \App\Models\MotivoFalhaAtendimento::orderBy('nome')->get();
        return view('administrativo.atendimentos.form', compact('pessoas', 'categorias', 'responsaveis', 'motivosFalha'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['operador_id'] = auth()->id();
        Atendimento::create($data);
        return redirect()->route('atendimentos.index')->with('success', 'Atendimento registrado com sucesso.');
    }

    public function edit(Atendimento $atendimento)
    {
        $pessoas = Pessoa::orderBy('nome')->get();
        $categorias = CategoriaAtendimento::orderBy('nome')->get();
        $responsaveis = \App\Models\User::where('ativo', true)->orderBy('nome')->get();
        $motivosFalha = \App\Models\MotivoFalhaAtendimento::orderBy('nome')->get();
        return view('administrativo.atendimentos.form', compact('atendimento', 'pessoas', 'categorias', 'responsaveis', 'motivosFalha'));
    }

    public function update(Request $request, Atendimento $atendimento)
    {
        // EDUQ: um atendimento finalizado nunca pode ser reaberto — nova dúvida exige novo protocolo
        if (in_array($atendimento->situacao, ['concluido', 'falha'], true)) {
            return redirect()->route('atendimentos.index')
                ->with('error', 'Atendimento finalizado não pode ser reaberto ou alterado. Abra um novo protocolo.');
        }

        $data = $this->validateData($request);
        $atendimento->update($data);
        return redirect()->route('atendimentos.index')->with('success', 'Atendimento atualizado com sucesso.');
    }

    public function destroy(Atendimento $atendimento)
    {
        $atendimento->delete();
        return redirect()->route('atendimentos.index')->with('success', 'Atendimento removido com sucesso.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'categoria_atendimento_id' => 'nullable|exists:categorias_atendimento,id',
            'descricao' => 'required|string',
            'situacao' => 'nullable|in:aberto,em_andamento,concluido,falha',
            'responsavel_id' => 'nullable|exists:users,id',
            'canal' => 'nullable|string|max:100',
            'portal_aluno' => 'nullable|boolean',
            'precisa_retorno' => 'nullable|boolean',
            'data_retorno' => 'nullable|date',
            'departamentos_responsavel' => 'nullable|boolean',
            'resolucao' => 'nullable|string',
            'objetivo_alcancado' => 'nullable|boolean',
            'motivo_falha_id' => 'nullable|exists:motivos_falha_atendimento,id',
        ], [
            'motivo_falha_id.required' => 'Ao finalizar sem alcançar o objetivo, selecione o motivo pré-cadastrado (alimenta os relatórios de auditoria).',
        ]);

        // EDUQ: ao fechar o chamado, marcar se o objetivo foi alcançado; se não, o motivo é obrigatório
        if (($data['situacao'] ?? null) === 'concluido' && !$request->boolean('objetivo_alcancado') && empty($data['motivo_falha_id'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'motivo_falha_id' => 'Ao finalizar sem alcançar o objetivo, selecione o motivo pré-cadastrado (alimenta os relatórios de auditoria).',
            ]);
        }

        return $data;
    }
}
