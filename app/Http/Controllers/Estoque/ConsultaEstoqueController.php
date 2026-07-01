<?php

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use App\Models\CategoriaEstoque;
use App\Models\ProdutoEstoque;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ConsultaEstoqueController extends Controller
{
    /** Consulta de Estoque (154). */
    public function index(Request $request)
    {
        $query = ProdutoEstoque::with(['categoriaEstoque', 'unidadeMedida']);
        if ($request->filled('categoria')) {
            $query->where('categoria_estoque_id', $request->categoria);
        }
        if ($request->filled('busca')) {
            $query->where(fn ($q) => $q->where('nome', 'like', '%' . $request->busca . '%')
                ->orWhere('codigo', 'like', '%' . $request->busca . '%'));
        }
        if ($request->boolean('abaixo_minimo')) {
            $query->whereColumn('estoque_atual', '<=', 'estoque_minimo');
        }
        $produtos = $query->orderBy('nome')->paginate(20)->withQueryString();

        $categorias = CategoriaEstoque::orderBy('nome')->get();
        $stats = [
            'itens' => ProdutoEstoque::count(),
            'abaixo_minimo' => ProdutoEstoque::whereColumn('estoque_atual', '<=', 'estoque_minimo')->count(),
            'valor_total' => ProdutoEstoque::sum(\Illuminate\Support\Facades\DB::raw('estoque_atual * preco_custo')),
        ];

        return view('estoque.consulta.index', compact('produtos', 'categorias', 'stats'));
    }

    /** Emissão de Produtos de Estoque (186). */
    public function emissao(Request $request)
    {
        $query = ProdutoEstoque::with(['categoriaEstoque', 'unidadeMedida']);
        if ($request->filled('categoria')) {
            $query->where('categoria_estoque_id', $request->categoria);
        }
        $produtos = $query->orderBy('nome')->get();

        $linhas = $produtos->map(fn ($p) => [
            $p->codigo ?? '—',
            $p->nome,
            $p->categoriaEstoque?->nome ?? '—',
            $p->estoque_atual . ' ' . ($p->unidadeMedida?->sigla ?? ''),
            $p->estoque_minimo,
            'R$ ' . number_format((float) $p->preco_custo, 2, ',', '.'),
            'R$ ' . number_format((float) $p->estoque_atual * (float) $p->preco_custo, 2, ',', '.'),
        ]);

        $titulo = 'Emissão de Produtos de Estoque';
        $subtitulo = $request->filled('categoria') ? 'Categoria: ' . (CategoriaEstoque::find($request->categoria)?->nome ?? '') : 'Todas as categorias';
        $colunas = ['Código', 'Produto', 'Categoria', 'Estoque', 'Mínimo', 'Custo unit.', 'Total'];
        $linhas = $linhas->map(fn ($l) => array_values((array) $l))->all();

        $pdf = Pdf::loadView('emissoes.academico.relatorio', compact('titulo', 'subtitulo', 'colunas', 'linhas'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('produtos_estoque.pdf');
    }
}
