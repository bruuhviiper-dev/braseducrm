<?php

namespace App\Http\Controllers\Biblioteca;

use App\Http\Controllers\Controller;
use App\Models\Biblioteca;
use App\Models\EstadoConservacao;
use App\Models\Exemplar;
use App\Models\Obra;
use App\Models\Pessoa;
use App\Models\TipoAquisicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExemplarController extends Controller
{
    public function index()
    {
        $exemplares = Exemplar::with('obra', 'biblioteca')->orderByDesc('id')->paginate(20);

        return view('biblioteca.exemplares.index', compact('exemplares'));
    }

    public function create()
    {
        return view('biblioteca.exemplares.form', $this->dados(null));
    }

    /** Quantidade gera N exemplares com código sequencial. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'obra_id' => 'required|exists:obras,id',
            'biblioteca_id' => 'required|exists:bibliotecas,id',
            'quantidade' => 'required|integer|min:1|max:500',
            'estado_conservacao_id' => 'nullable|exists:estados_conservacao,id',
            'doador_pessoa_id' => 'nullable|exists:pessoas,id',
            'tipo_aquisicao_id' => 'nullable|exists:tipos_aquisicao,id',
            'valor_compra' => 'nullable|numeric|min:0',
            'data_aquisicao' => 'nullable|date',
            'copia_local' => 'nullable|boolean',
        ]);

        $quantidade = (int) $data['quantidade'];
        unset($data['quantidade']);
        $data['copia_local'] = $request->boolean('copia_local');

        DB::transaction(function () use ($data, $quantidade) {
            $base = Exemplar::where('obra_id', $data['obra_id'])->count();
            for ($i = 1; $i <= $quantidade; $i++) {
                Exemplar::create(array_merge($data, [
                    'codigo' => 'EX-' . $data['obra_id'] . '-' . ($base + $i),
                    'situacao' => 'disponivel',
                ]));
            }
        });

        return redirect()->route('biblioteca.exemplares.index')
            ->with('success', $quantidade . ' exemplar(es) cadastrado(s).');
    }

    public function edit(Exemplar $exemplare)
    {
        return view('biblioteca.exemplares.form', $this->dados($exemplare));
    }

    public function update(Request $request, Exemplar $exemplare)
    {
        $data = $request->validate([
            'obra_id' => 'required|exists:obras,id',
            'biblioteca_id' => 'required|exists:bibliotecas,id',
            'codigo' => 'nullable|string|max:255',
            'estado_conservacao_id' => 'nullable|exists:estados_conservacao,id',
            'doador_pessoa_id' => 'nullable|exists:pessoas,id',
            'tipo_aquisicao_id' => 'nullable|exists:tipos_aquisicao,id',
            'valor_compra' => 'nullable|numeric|min:0',
            'data_aquisicao' => 'nullable|date',
            'copia_local' => 'nullable|boolean',
            'situacao' => 'required|in:' . implode(',', Exemplar::SITUACOES),
        ]);
        $data['copia_local'] = $request->boolean('copia_local');

        $exemplare->update($data);

        return redirect()->route('biblioteca.exemplares.index')->with('success', 'Exemplar atualizado.');
    }

    public function destroy(Exemplar $exemplare)
    {
        $exemplare->delete();

        return redirect()->route('biblioteca.exemplares.index')->with('success', 'Exemplar removido.');
    }

    private function dados(?Exemplar $exemplar): array
    {
        return [
            'exemplar' => $exemplar,
            'obras' => Obra::orderBy('titulo')->get(),
            'bibliotecas' => Biblioteca::orderBy('nome')->get(),
            'estados' => EstadoConservacao::orderBy('nome')->get(),
            'tiposAquisicao' => TipoAquisicao::orderBy('nome')->get(),
            'pessoas' => Pessoa::orderBy('nome')->get(),
        ];
    }
}
