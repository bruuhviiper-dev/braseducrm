<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\TituloReceber;
use App\Models\Pessoa;
use App\Models\CategoriaReceber;
use App\Models\ContaBancaria;
use App\Services\BoletoCnabService;
use Illuminate\Http\Request;

class TituloReceberController extends Controller
{
    public function index(Request $request)
    {
        $query = TituloReceber::with(['pessoa', 'categoriaReceber']);

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

        $totalAberto = TituloReceber::where('situacao', 'aberto')->sum('valor_original');
        $totalPago = TituloReceber::where('situacao', 'pago')->sum('valor_pago');
        $totalVencido = TituloReceber::where('situacao', 'aberto')
            ->where('data_vencimento', '<', now())
            ->sum('valor_original');
        $totalCancelado = TituloReceber::where('situacao', 'cancelado')->sum('valor_original');

        return view('financeiro.titulos-receber.index', compact(
            'titulos', 'totalAberto', 'totalPago', 'totalVencido', 'totalCancelado'
        ));
    }

    public function gerarRemessa(BoletoCnabService $cnab)
    {
        if (!$cnab->configurado()) {
            return back()->with('error', 'Configure a integração de Boleto (banco, agência, conta) em Integrações antes de gerar a remessa.');
        }

        $titulos = TituloReceber::with('pessoa')
            ->where('situacao', 'aberto')
            ->orderBy('data_vencimento')
            ->get();

        if ($titulos->isEmpty()) {
            return back()->with('error', 'Não há títulos em aberto para gerar a remessa.');
        }

        $conteudo = $cnab->gerarRemessa($titulos);
        $nome = 'remessa_' . now()->format('Ymd_His') . '.rem';

        return response($conteudo, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $nome . '"',
        ]);
    }

    public function create()
    {
        $pessoas = Pessoa::orderBy('nome')->get();
        $categorias = CategoriaReceber::orderBy('nome')->get();
        $contas = ContaBancaria::where('ativo', true)->orderBy('nome')->get();

        return view('financeiro.titulos-receber.form', compact('pessoas', 'categorias', 'contas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'categoria_receber_id' => 'nullable|exists:categorias_receber,id',
            'conta_bancaria_id' => 'nullable|exists:contas_bancarias,id',
            'valor_original' => 'required|numeric|min:0.01',
            'valor_desconto' => 'nullable|numeric|min:0',
            'data_emissao' => 'required|date',
            'data_vencimento' => 'required|date',
            'forma_pagamento' => 'nullable|string|max:50',
            'observacoes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['situacao'] = 'aberto';

        TituloReceber::create($data);

        return redirect()->route('financeiro.titulos-receber.index')
            ->with('success', 'Titulo a receber cadastrado com sucesso.');
    }

    public function edit(TituloReceber $titulos_receber)
    {
        $titulo = $titulos_receber;
        $pessoas = Pessoa::orderBy('nome')->get();
        $categorias = CategoriaReceber::orderBy('nome')->get();
        $contas = ContaBancaria::where('ativo', true)->orderBy('nome')->get();

        return view('financeiro.titulos-receber.form', compact('titulo', 'pessoas', 'categorias', 'contas'));
    }

    public function update(Request $request, TituloReceber $titulos_receber)
    {
        $titulo = $titulos_receber;

        $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'categoria_receber_id' => 'nullable|exists:categorias_receber,id',
            'conta_bancaria_id' => 'nullable|exists:contas_bancarias,id',
            'valor_original' => 'required|numeric|min:0.01',
            'valor_desconto' => 'nullable|numeric|min:0',
            'data_emissao' => 'required|date',
            'data_vencimento' => 'required|date',
            'forma_pagamento' => 'nullable|string|max:50',
            'observacoes' => 'nullable|string',
        ]);

        $titulo->update($request->all());

        return redirect()->route('financeiro.titulos-receber.index')
            ->with('success', 'Titulo a receber atualizado com sucesso.');
    }

    public function destroy(TituloReceber $titulos_receber)
    {
        $titulos_receber->delete();

        return redirect()->route('financeiro.titulos-receber.index')
            ->with('success', 'Titulo a receber removido com sucesso.');
    }

    public function baixar(Request $request, TituloReceber $titulo)
    {
        $request->validate([
            'valor_pago' => 'nullable|numeric|min:0',
            'data_pagamento' => 'nullable|date',
        ]);

        $titulo->update([
            'situacao' => 'pago',
            'valor_pago' => $request->get('valor_pago', $titulo->valor_original - ($titulo->valor_desconto ?? 0)),
            'data_pagamento' => $request->get('data_pagamento', now()),
        ]);

        return redirect()->route('financeiro.titulos-receber.index')
            ->with('success', 'Titulo baixado com sucesso.');
    }
}
