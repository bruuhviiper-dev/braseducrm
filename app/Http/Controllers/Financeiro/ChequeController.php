<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use App\Models\Cheque;
use App\Models\MotivoDevolucaoCheque;
use Illuminate\Http\Request;

class ChequeController extends Controller
{
    public function index(Request $request)
    {
        $query = Cheque::with(['banco', 'motivoDevolucao']);
        if ($request->filled('situacao')) {
            $query->where('situacao', $request->situacao);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        $cheques = $query->orderByDesc('id')->paginate(20)->withQueryString();

        $totais = [
            'carteira' => Cheque::where('situacao', 'carteira')->sum('valor'),
            'devolvido' => Cheque::where('situacao', 'devolvido')->sum('valor'),
        ];

        $motivos = MotivoDevolucaoCheque::where('ativo', true)->orderBy('nome')->get();

        return view('financeiro.cheques.index', compact('cheques', 'totais', 'motivos'));
    }

    public function create()
    {
        return view('financeiro.cheques.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        Cheque::create($this->validar($request));

        return redirect()->route('financeiro.cheques.index')->with('success', 'Cheque cadastrado com sucesso.');
    }

    public function edit(Cheque $cheque)
    {
        return view('financeiro.cheques.form', $this->dados($cheque));
    }

    public function update(Request $request, Cheque $cheque)
    {
        $cheque->update($this->validar($request));

        return redirect()->route('financeiro.cheques.index')->with('success', 'Cheque atualizado.');
    }

    public function destroy(Cheque $cheque)
    {
        $cheque->delete();

        return redirect()->route('financeiro.cheques.index')->with('success', 'Cheque removido.');
    }

    /** Altera a situação do cheque (depositar/compensar/devolver/repassar). */
    public function situacao(Request $request, Cheque $cheque)
    {
        $data = $request->validate([
            'situacao' => 'required|in:' . implode(',', array_keys(Cheque::SITUACOES)),
            'motivo_devolucao_id' => 'nullable|exists:motivos_devolucao_cheque,id',
        ]);

        // Motivo só faz sentido quando devolvido; limpa nos demais casos.
        $cheque->update([
            'situacao' => $data['situacao'],
            'motivo_devolucao_id' => $data['situacao'] === 'devolvido' ? ($data['motivo_devolucao_id'] ?? null) : null,
        ]);

        return redirect()->route('financeiro.cheques.index')->with('success', 'Situação do cheque atualizada para ' . Cheque::SITUACOES[$data['situacao']] . '.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'tipo' => 'required|in:' . implode(',', array_keys(Cheque::TIPOS)),
            'numero' => 'required|string|max:255',
            'banco_id' => 'nullable|exists:bancos,id',
            'agencia' => 'nullable|string|max:255',
            'conta' => 'nullable|string|max:255',
            'emitente' => 'nullable|string|max:255',
            'valor' => 'required|numeric|min:0',
            'bom_para' => 'nullable|date',
            'situacao' => 'required|in:' . implode(',', array_keys(Cheque::SITUACOES)),
            'observacao' => 'nullable|string',
        ]);
    }

    private function dados(?Cheque $cheque): array
    {
        return [
            'cheque' => $cheque,
            'bancos' => Banco::where('ativo', true)->orderBy('nome')->get(),
            'motivos' => MotivoDevolucaoCheque::where('ativo', true)->orderBy('nome')->get(),
        ];
    }
}
