<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\EmissaoLayout;
use Illuminate\Http\Request;

/** Gestão genérica dos Layouts salvos das emissões (usado pelo componente x-report-builder). */
class EmissaoLayoutController extends Controller
{
    public function salvar(Request $request)
    {
        $v = $request->validate([
            'funcao_codigo' => 'required|integer',
            'nome' => 'required|string|max:100',
            'colunas' => 'required|array|min:1',
            'colunas.*' => 'string',
            'padrao' => 'nullable|boolean',
            'compartilhado' => 'nullable|boolean',
        ]);
        if ($request->boolean('padrao')) {
            EmissaoLayout::where('user_id', auth()->id())->where('funcao_codigo', $v['funcao_codigo'])->update(['padrao' => false]);
        }
        EmissaoLayout::create([
            'user_id' => auth()->id(),
            'funcao_codigo' => $v['funcao_codigo'],
            'nome' => $v['nome'],
            'colunas' => array_values($v['colunas']),
            'padrao' => $request->boolean('padrao'),
            'compartilhado' => $request->boolean('compartilhado'),
        ]);

        return back()->with('success', 'Layout salvo.');
    }

    public function excluir(EmissaoLayout $layout)
    {
        abort_unless($layout->user_id === auth()->id(), 403);
        $layout->delete();

        return back()->with('success', 'Layout removido.');
    }
}
