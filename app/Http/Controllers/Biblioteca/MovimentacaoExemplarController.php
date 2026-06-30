<?php

namespace App\Http\Controllers\Biblioteca;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoBiblioteca;
use App\Models\Exemplar;
use App\Models\MovimentacaoExemplar;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MovimentacaoExemplarController extends Controller
{
    public function index()
    {
        $movimentacoes = MovimentacaoExemplar::with('exemplar.obra', 'pessoa')->orderByDesc('id')->paginate(20);

        return view('biblioteca.movimentacoes.index', compact('movimentacoes'));
    }

    public function create()
    {
        $config = ConfiguracaoBiblioteca::current();
        $previsao = now()->addDays($config->dias_devolucao)->format('Y-m-d');

        return view('biblioteca.movimentacoes.form', [
            'exemplares' => Exemplar::with('obra')->where('situacao', 'disponivel')->get(),
            'pessoas' => Pessoa::orderBy('nome')->get(),
            'previsao' => $previsao,
        ]);
    }

    /** Registra o empréstimo e marca o exemplar como emprestado. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'exemplar_id' => 'required|exists:exemplares,id',
            'pessoa_id' => 'required|exists:pessoas,id',
            'data_emprestimo' => 'required|date',
            'data_prevista_devolucao' => 'required|date|after_or_equal:data_emprestimo',
        ]);

        DB::transaction(function () use ($data) {
            MovimentacaoExemplar::create($data + ['situacao' => 'emprestado']);
            Exemplar::where('id', $data['exemplar_id'])->update(['situacao' => 'emprestado']);
        });

        return redirect()->route('biblioteca.movimentacoes.index')->with('success', 'Empréstimo registrado.');
    }

    /** Devolução: calcula multa (se configurada) e libera o exemplar. */
    public function devolver(MovimentacaoExemplar $movimentacao)
    {
        if ($movimentacao->situacao === 'devolvido') {
            return back()->with('error', 'Este empréstimo já foi devolvido.');
        }

        $config = ConfiguracaoBiblioteca::current();
        $hoje = now()->startOfDay();
        $multa = 0;
        if ($config->aplicar_multa) {
            $prevista = Carbon::parse($movimentacao->data_prevista_devolucao)->startOfDay();
            if ($hoje->gt($prevista)) {
                $diasAtraso = abs($prevista->diffInDays($hoje));
                $multa = $diasAtraso * (float) $config->valor_diario;
            }
        }

        DB::transaction(function () use ($movimentacao, $hoje, $multa) {
            $movimentacao->update([
                'data_devolucao' => $hoje->toDateString(),
                'multa' => $multa,
                'situacao' => 'devolvido',
            ]);
            $movimentacao->exemplar()->update(['situacao' => 'disponivel']);
        });

        $msg = 'Devolução registrada.' . ($multa > 0 ? ' Multa de R$ ' . number_format($multa, 2, ',', '.') . '.' : '');
        return redirect()->route('biblioteca.movimentacoes.index')->with('success', $msg);
    }

    public function destroy(MovimentacaoExemplar $movimentacao)
    {
        if ($movimentacao->situacao === 'emprestado') {
            $movimentacao->exemplar()->update(['situacao' => 'disponivel']);
        }
        $movimentacao->delete();

        return redirect()->route('biblioteca.movimentacoes.index')->with('success', 'Movimentação removida.');
    }
}
