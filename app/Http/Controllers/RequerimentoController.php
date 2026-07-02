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
        Requerimento::create($data);

        return redirect()->route('requerimentos.index')->with('success', 'Requerimento criado com sucesso.');
    }

    public function edit(Requerimento $requerimento)
    {
        return view('administrativo.requerimentos.form', $this->dados($requerimento));
    }

    public function update(Request $request, Requerimento $requerimento)
    {
        $requerimento->update($this->validar($request));

        return redirect()->route('requerimentos.index')->with('success', 'Requerimento atualizado com sucesso.');
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
