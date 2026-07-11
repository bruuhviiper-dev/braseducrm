<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\FormaIngresso;
use App\Models\FormaPagamento;
use App\Models\Matricula;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatriculaController extends Controller
{
    public function index()
    {
        $matriculas = Matricula::with(['aluno.pessoa', 'turma.curso', 'turmaMontada', 'consultor', 'assinaturasEletronicas'])->orderByDesc('id')->paginate(15);

        return view('academico.matriculas.index', compact('matriculas'));
    }

    public function create()
    {
        return view('academico.matriculas.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        DB::transaction(function () use ($data) {
            $matricula = Matricula::create($data['matricula']);
            $this->salvarFilhos($matricula, $data);
        });

        return redirect()->route('academico.matriculas.index')
            ->with('success', 'Matricula realizada com sucesso.');
    }

    public function edit(Matricula $matricula)
    {
        $matricula->load(['aluno.pessoa', 'documentos']);

        return view('academico.matriculas.form', $this->dados($matricula));
    }

    public function update(Request $request, Matricula $matricula)
    {
        $data = $this->validar($request);
        DB::transaction(function () use ($matricula, $data) {
            $matricula->update($data['matricula']);
            $this->salvarFilhos($matricula, $data);
        });

        return redirect()->route('academico.matriculas.index')
            ->with('success', 'Matricula atualizada com sucesso.');
    }

    /**
     * Cancelar matrícula (doc 242): o motivo de cancelamento é obrigatório.
     * Se não houver motivos cadastrados, o sistema direciona para a 242.
     */
    public function cancelar(\Illuminate\Http\Request $request, Matricula $matricula)
    {
        $v = $request->validate(['motivo_cancelamento_id' => 'required|exists:motivos_cancelamento_matricula,id']);
        $motivo = \App\Models\MotivoCancelamentoMatricula::find($v['motivo_cancelamento_id']);
        $matricula->update(['situacao' => 'cancelada', 'observacoes' => ($matricula->observacoes ? $matricula->observacoes . "\n" : '') . 'Cancelado: ' . $motivo?->nome]);
        \App\Models\MovimentacaoMatricula::registrar($matricula->id, 'Matrícula cancelada. Motivo: ' . ($motivo?->nome ?? '-'), 'cancelada', null);

        return back()->with('success', 'Matrícula cancelada com motivo registrado.');
    }

    public function destroy(Matricula $matricula)
    {
        $matricula->delete();

        return redirect()->route('academico.matriculas.index')
            ->with('success', 'Matricula removida com sucesso.');
    }

    private function validar(Request $request): array
    {
        $v = $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'turma_id' => 'required|exists:turmas,id',
            'numero_matricula' => 'nullable|string|max:50',
            'data_matricula' => 'required|date',
            'situacao' => 'required|string|max:50',
            'forma_ingresso_id' => 'nullable|exists:formas_ingresso,id',
            'observacoes' => 'nullable|string',
            // plano de pagamento
            'valor_total' => 'nullable|numeric|min:0',
            'desconto' => 'nullable|numeric|min:0',
            'num_parcelas' => 'nullable|integer|min:1|max:120',
            'valor_parcela' => 'nullable|numeric|min:0',
            'dia_vencimento' => 'nullable|integer|min:1|max:31',
            'primeiro_vencimento' => 'nullable|date',
            'forma_pagamento_id' => 'nullable|exists:formas_pagamento,id',
            // documentos
            'documentos' => 'nullable|array',
            'documentos.*.documento' => 'nullable|string|max:255',
            'documentos.*.entregue' => 'nullable',
            'documentos.*.observacao' => 'nullable|string|max:255',
        ]);

        return [
            'matricula' => [
                'aluno_id' => $v['aluno_id'],
                'turma_id' => $v['turma_id'],
                'numero_matricula' => $v['numero_matricula'] ?? null,
                'data_matricula' => $v['data_matricula'],
                'situacao' => $v['situacao'],
                'forma_ingresso_id' => $v['forma_ingresso_id'] ?? null,
                'observacoes' => $v['observacoes'] ?? null,
                'valor_total' => $v['valor_total'] ?? null,
                'desconto' => $v['desconto'] ?? null,
                'num_parcelas' => $v['num_parcelas'] ?? null,
                'valor_parcela' => $v['valor_parcela'] ?? null,
                'dia_vencimento' => $v['dia_vencimento'] ?? null,
                'primeiro_vencimento' => $v['primeiro_vencimento'] ?? null,
                'forma_pagamento_id' => $v['forma_pagamento_id'] ?? null,
            ],
            'documentos' => collect($v['documentos'] ?? [])
                ->filter(fn ($d) => !empty($d['documento']))
                ->map(fn ($d) => [
                    'documento' => $d['documento'],
                    'entregue' => !empty($d['entregue']),
                    'observacao' => $d['observacao'] ?? null,
                ])->values()->all(),
        ];
    }

    private function salvarFilhos(Matricula $matricula, array $data): void
    {
        $matricula->documentos()->delete();
        foreach ($data['documentos'] as $d) {
            $matricula->documentos()->create($d);
        }
    }

    private function dados(?Matricula $matricula): array
    {
        return [
            'matricula' => $matricula,
            'alunos' => Aluno::with('pessoa')->get(),
            'turmas' => Turma::orderBy('nome')->get(),
            'formasIngresso' => FormaIngresso::orderBy('nome')->get(),
            'formasPagamento' => FormaPagamento::orderBy('nome')->get(),
        ];
    }
}
