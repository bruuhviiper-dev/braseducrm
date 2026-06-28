<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\TituloPagar;
use App\Models\Pessoa;
use App\Models\CategoriaPagar;
use App\Models\ContaBancaria;
use Illuminate\Http\Request;

class TituloPagarController extends Controller
{
    public function index(Request $request)
    {
        $query = TituloPagar::with(['pessoa', 'categoriaPagar']);

        if ($search = $request->get('search')) {
            $query->whereHas('pessoa', function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%");
            });
        }

        if ($situacao = $request->get('situacao')) {
            $query->where('situacao', $situacao);
        }

        if ($data_inicio = $request->get('data_inicio')) {
            $query->where('data_vencimento', '>=', $data_inicio);
        }

        if ($data_fim = $request->get('data_fim')) {
            $query->where('data_vencimento', '<=', $data_fim);
        }

        $titulos = $query->orderByDesc('data_vencimento')->paginate(15)->withQueryString();

        $totalAberto = TituloPagar::where('situacao', 'aberto')->sum('valor_original');
        $totalPago = TituloPagar::where('situacao', 'pago')->sum('valor_pago');
        $totalVencido = TituloPagar::where('situacao', 'aberto')
            ->where('data_vencimento', '<', now())
            ->sum('valor_original');
        $totalCancelado = TituloPagar::where('situacao', 'cancelado')->sum('valor_original');

        return view('financeiro.titulos-pagar.index', compact(
            'titulos', 'totalAberto', 'totalPago', 'totalVencido', 'totalCancelado'
        ));
    }

    public function create()
    {
        $pessoas = Pessoa::orderBy('nome')->get();
        $categorias = CategoriaPagar::orderBy('nome')->get();
        $contas = ContaBancaria::where('ativo', true)->orderBy('nome')->get();

        return view('financeiro.titulos-pagar.form', compact('pessoas', 'categorias', 'contas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'categoria_pagar_id' => 'nullable|exists:categorias_pagar,id',
            'descricao' => 'nullable|string|max:255',
            'valor_original' => 'required|numeric|min:0.01',
            'data_emissao' => 'required|date',
            'data_vencimento' => 'required|date',
            'observacoes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['situacao'] = 'aberto';

        TituloPagar::create($data);

        return redirect()->route('financeiro.titulos-pagar.index')
            ->with('success', 'Titulo a pagar cadastrado com sucesso.');
    }

    public function edit(TituloPagar $titulos_pagar)
    {
        $titulo = $titulos_pagar;
        $pessoas = Pessoa::orderBy('nome')->get();
        $categorias = CategoriaPagar::orderBy('nome')->get();
        $contas = ContaBancaria::where('ativo', true)->orderBy('nome')->get();

        return view('financeiro.titulos-pagar.form', compact('titulo', 'pessoas', 'categorias', 'contas'));
    }

    public function update(Request $request, TituloPagar $titulos_pagar)
    {
        $titulo = $titulos_pagar;

        $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'categoria_pagar_id' => 'nullable|exists:categorias_pagar,id',
            'descricao' => 'nullable|string|max:255',
            'valor_original' => 'required|numeric|min:0.01',
            'data_emissao' => 'required|date',
            'data_vencimento' => 'required|date',
            'observacoes' => 'nullable|string',
        ]);

        $titulo->update($request->all());

        return redirect()->route('financeiro.titulos-pagar.index')
            ->with('success', 'Titulo a pagar atualizado com sucesso.');
    }

    public function destroy(TituloPagar $titulos_pagar)
    {
        $titulos_pagar->delete();

        return redirect()->route('financeiro.titulos-pagar.index')
            ->with('success', 'Titulo a pagar removido com sucesso.');
    }
}
