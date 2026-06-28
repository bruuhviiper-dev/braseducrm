<?php

namespace App\Http\Controllers;

use App\Models\Integracao;
use App\Services\RdStationService;
use Illuminate\Http\Request;

class IntegracoesController extends Controller
{
    public function index()
    {
        $catalogo = Integracao::catalogo();
        $configuradas = Integracao::all()->keyBy('chave');
        return view('integracoes.index', compact('catalogo', 'configuradas'));
    }

    public function edit(string $chave)
    {
        $catalogo = Integracao::catalogo();
        abort_unless(isset($catalogo[$chave]), 404);

        $definicao = $catalogo[$chave];
        $integracao = Integracao::firstOrNew(['chave' => $chave]);

        return view('integracoes.form', compact('chave', 'definicao', 'integracao'));
    }

    public function update(Request $request, string $chave)
    {
        $catalogo = Integracao::catalogo();
        abort_unless(isset($catalogo[$chave]), 404);

        $campos = array_keys($catalogo[$chave]['campos']);
        $credenciais = [];
        foreach ($campos as $campo) {
            $credenciais[$campo] = $request->input("cred_$campo");
        }

        Integracao::updateOrCreate(
            ['chave' => $chave],
            [
                'nome' => $catalogo[$chave]['nome'],
                'ativo' => $request->boolean('ativo'),
                'credenciais' => $credenciais,
            ]
        );

        return redirect()->route('integracoes.index')->with('success', 'Integração salva com sucesso.');
    }

    public function testar(string $chave)
    {
        if ($chave === 'rd_station') {
            $resultado = (new RdStationService())->testarConexao();
            return back()->with($resultado['ok'] ? 'success' : 'error', $resultado['mensagem']);
        }

        return back()->with('error', 'Teste de conexão ainda não disponível para esta integração.');
    }
}
