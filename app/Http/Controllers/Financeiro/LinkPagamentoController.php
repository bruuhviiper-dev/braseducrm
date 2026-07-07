<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\CategoriaReceber;
use App\Models\Pessoa;
use App\Models\TituloReceber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * 230 - Link de Pagamento Avulso: gera um link dinâmico de pagamento (Pix/Cartão)
 * para enviar por e-mail/WhatsApp; ao pagar pelo link, a baixa é automática (como no gateway).
 */
class LinkPagamentoController extends Controller
{
    public function index()
    {
        $links = TituloReceber::with('pessoa')
            ->whereNotNull('token_pagamento')
            ->orderByDesc('id')->paginate(20);
        $pessoas = Pessoa::orderBy('nome')->get();
        $categorias = CategoriaReceber::orderBy('nome')->get();

        return view('financeiro.link-pagamento.index', compact('links', 'pessoas', 'categorias'));
    }

    public function gerar(Request $request)
    {
        $data = $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'valor' => 'required|numeric|min:0.01',
            'vencimento' => 'required|date',
            'categoria_receber_id' => 'nullable|exists:categorias_receber,id',
            'descricao' => 'nullable|string|max:255',
        ]);

        $titulo = TituloReceber::create([
            'pessoa_id' => $data['pessoa_id'],
            'categoria_receber_id' => $data['categoria_receber_id'] ?? null,
            'valor_original' => $data['valor'],
            'data_emissao' => now(),
            'data_vencimento' => $data['vencimento'],
            'situacao' => 'aberto',
            'forma_pagamento' => 'pix',
            'observacoes' => $data['descricao'] ?? 'Link de pagamento avulso',
            'token_pagamento' => Str::random(32),
        ]);

        return redirect()->route('financeiro.link-pagamento.index')
            ->with('success', 'Link gerado com sucesso.')
            ->with('link_gerado', route('pagamento.publico', $titulo->token_pagamento));
    }

    /** Página pública do link (o aluno abre sem login). */
    public function publico(string $token)
    {
        $titulo = TituloReceber::with('pessoa')->where('token_pagamento', $token)->firstOrFail();

        return view('financeiro.link-pagamento.publico', compact('titulo'));
    }

    /** Simulação de pagamento via gateway: liquida o título e dá baixa automática. */
    public function pagar(string $token)
    {
        $titulo = TituloReceber::where('token_pagamento', $token)->firstOrFail();

        if ($titulo->situacao === 'aberto') {
            $titulo->update([
                'situacao' => 'pago',
                'valor_pago' => $titulo->valor_original - ($titulo->valor_desconto ?? 0),
                'data_pagamento' => now(),
            ]);
        }

        return redirect()->route('pagamento.publico', $token)->with('success', 'Pagamento confirmado! A baixa foi processada automaticamente.');
    }
}
