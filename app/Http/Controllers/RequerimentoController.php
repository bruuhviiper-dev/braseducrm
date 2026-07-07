<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Models\MatriculaEad;
use App\Models\Pessoa;
use App\Models\Requerimento;
use App\Models\TipoRequerimento;
use Illuminate\Http\Request;

class RequerimentoController extends Controller
{
    public function index()
    {
        $requerimentos = Requerimento::with(['aluno.pessoa', 'pessoa', 'tipoRequerimento'])
            ->orderBy('id', 'desc')->paginate(20);

        return view('administrativo.requerimentos.index', compact('requerimentos'));
    }

    public function create()
    {
        return view('administrativo.requerimentos.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $data['operador_id'] = auth()->id();

        $tipo = TipoRequerimento::find($data['tipo_requerimento_id']);
        $pessoaId = $this->pessoaDoVinculo($data);

        // Regras de bloqueio do EDUQ (Ecrã 94)
        if ($tipo && $pessoaId) {
            if ($tipo->bloquear_inadimplente && \App\Models\TituloReceber::where('pessoa_id', $pessoaId)
                    ->where('situacao', 'aberto')->where('data_vencimento', '<', now())->exists()) {
                return back()->withInput()->withErrors([
                    'tipo_requerimento_id' => 'Bloqueio de inadimplência: este aluno possui parcelas vencidas em aberto e não pode abrir este requerimento.',
                ]);
            }
            if ($tipo->bloquear_parcelas_abertas && \App\Models\TituloReceber::where('pessoa_id', $pessoaId)
                    ->where('situacao', 'aberto')->exists()) {
                return back()->withInput()->withErrors([
                    'tipo_requerimento_id' => 'Este requerimento exige a quitação de todo o plano financeiro: há parcelas em aberto (mesmo a vencer).',
                ]);
            }
        }

        $requerimento = Requerimento::create($data);

        // Cobrança automática: fora da cota de isenção, gera o título com vencimento dinâmico em dias
        if ($tipo && $pessoaId && !$tipo->isento && (float) $tipo->valor > 0) {
            $usoAnterior = Requerimento::where('tipo_requerimento_id', $tipo->id)
                ->where('id', '!=', $requerimento->id)
                ->when($data['aluno_id'], fn ($q) => $q->where('aluno_id', $data['aluno_id']), fn ($q) => $q->where('pessoa_id', $pessoaId))
                ->count();

            if ($tipo->cota_isencao === null || $usoAnterior >= (int) $tipo->cota_isencao) {
                \App\Models\TituloReceber::create([
                    'pessoa_id' => $pessoaId,
                    'matricula_id' => $data['matricula_id'],
                    'categoria_receber_id' => $tipo->categoria_receber_id,
                    'conta_bancaria_id' => $tipo->conta_bancaria_id,
                    'valor_original' => $tipo->valor,
                    'data_emissao' => now(),
                    'data_vencimento' => now()->addWeekdays($tipo->vencimento_dias ?: 10),
                    'situacao' => 'aberto',
                    'observacoes' => 'Taxa de requerimento: ' . $tipo->nome,
                ]);

                return redirect()->route('requerimentos.index')
                    ->with('success', 'Requerimento criado. Taxa de R$ ' . number_format((float) $tipo->valor, 2, ',', '.') . ' gerada no Contas a Receber (vencimento em ' . ($tipo->vencimento_dias ?: 10) . ' dias úteis).');
            }
        }

        return redirect()->route('requerimentos.index')->with('success', 'Requerimento criado com sucesso.');
    }

    public function edit(Requerimento $requerimento)
    {
        return view('administrativo.requerimentos.form', $this->dados($requerimento));
    }

    public function update(Request $request, Requerimento $requerimento)
    {
        $data = $this->validar($request);
        $requerimento->update($data);

        // EDUQ: requerimento aprovado pode alterar automaticamente o estado da matrícula (Trancado/Desistente/Cancelado)
        $tipo = TipoRequerimento::find($data['tipo_requerimento_id']);
        if ($tipo && $tipo->novo_status_matricula && $data['situacao'] === 'aprovado' && $data['matricula_id']) {
            Matricula::where('id', $data['matricula_id'])->update(['situacao' => $tipo->novo_status_matricula]);

            return redirect()->route('requerimentos.index')
                ->with('success', 'Requerimento aprovado. A matrícula do aluno foi alterada automaticamente para "' . ucfirst($tipo->novo_status_matricula) . '".');
        }

        return redirect()->route('requerimentos.index')->with('success', 'Requerimento atualizado com sucesso.');
    }

    /** Resolve a pessoa (pagadora) a partir do vínculo do requerimento. */
    private function pessoaDoVinculo(array $data): ?int
    {
        if (!empty($data['pessoa_id'])) {
            return $data['pessoa_id'];
        }
        if (!empty($data['aluno_id'])) {
            return optional(\App\Models\Aluno::find($data['aluno_id']))->pessoa_id;
        }
        return null;
    }

    public function destroy(Requerimento $requerimento)
    {
        $requerimento->delete();

        return redirect()->route('requerimentos.index')->with('success', 'Requerimento removido com sucesso.');
    }

    private function validar(Request $request): array
    {
        $v = $request->validate([
            'vinculo_tipo' => 'required|in:pessoa,matricula,matricula_ead',
            'pessoa_id' => 'nullable|exists:pessoas,id',
            'matricula_id' => 'nullable|exists:matriculas,id',
            'matricula_ead_id' => 'nullable|exists:matriculas_ead,id',
            'tipo_requerimento_id' => 'required|exists:tipos_requerimento,id',
            'situacao' => 'required|in:pendente,aprovado,reprovado,cancelado,entregue',
            'descricao' => 'nullable|string',
            'observacoes' => 'nullable|string',
            'anotacoes' => 'nullable|string',
        ]);

        // deriva aluno_id conforme o vínculo (EDUQ: Pessoa / Matrícula / Matrícula EAD)
        $alunoId = null;
        $pessoaId = null;
        $matriculaId = null;
        $matriculaEadId = null;

        switch ($v['vinculo_tipo']) {
            case 'matricula':
                $matriculaId = $v['matricula_id'] ?? null;
                $alunoId = $matriculaId ? optional(Matricula::find($matriculaId))->aluno_id : null;
                break;
            case 'matricula_ead':
                $matriculaEadId = $v['matricula_ead_id'] ?? null;
                $alunoId = $matriculaEadId ? optional(MatriculaEad::find($matriculaEadId))->aluno_id : null;
                break;
            case 'pessoa':
                $pessoaId = $v['pessoa_id'] ?? null;
                $alunoId = $pessoaId ? optional(optional(Pessoa::find($pessoaId))->aluno)->id : null;
                break;
        }

        return [
            'vinculo_tipo' => $v['vinculo_tipo'],
            'pessoa_id' => $pessoaId,
            'matricula_id' => $matriculaId,
            'matricula_ead_id' => $matriculaEadId,
            'aluno_id' => $alunoId,
            'tipo_requerimento_id' => $v['tipo_requerimento_id'],
            'situacao' => $v['situacao'],
            'descricao' => $v['descricao'] ?? null,
            'observacoes' => $v['observacoes'] ?? null,
            'anotacoes' => $v['anotacoes'] ?? null,
        ];
    }

    private function dados(?Requerimento $requerimento): array
    {
        return [
            'requerimento' => $requerimento,
            'pessoas' => Pessoa::orderBy('nome')->get(),
            'matriculas' => Matricula::with('aluno.pessoa')->get(),
            'matriculasEad' => MatriculaEad::with('aluno.pessoa')->get(),
            'tipos' => TipoRequerimento::where('ativo', true)->orderBy('nome')->get(),
        ];
    }
}
