<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\EstruturaPlano;
use App\Models\TopicoPlano;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstruturaPlanoController extends Controller
{
    public function index()
    {
        $estruturas = EstruturaPlano::withCount('topicos')->orderBy('nome')->paginate(20);

        return view('academico.estruturas-plano.index', compact('estruturas'));
    }

    public function create()
    {
        $topicos = TopicoPlano::orderBy('nome')->get();
        $estrutura = null;

        return view('academico.estruturas-plano.form', compact('topicos', 'estrutura'));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $estrutura = EstruturaPlano::create(['nome' => $data['nome']]);
        $this->sincronizarTopicos($estrutura, $data['topicos'] ?? []);

        return redirect()->route('academico.estruturas-plano.index')
            ->with('success', 'Estrutura do plano cadastrada com sucesso.');
    }

    public function edit(EstruturaPlano $estruturas_plano)
    {
        $topicos = TopicoPlano::orderBy('nome')->get();
        $estrutura = $estruturas_plano->load('topicos');

        return view('academico.estruturas-plano.form', compact('topicos', 'estrutura'));
    }

    public function update(Request $request, EstruturaPlano $estruturas_plano)
    {
        $data = $this->validar($request);
        $estruturas_plano->update(['nome' => $data['nome']]);
        $this->sincronizarTopicos($estruturas_plano, $data['topicos'] ?? []);

        return redirect()->route('academico.estruturas-plano.index')
            ->with('success', 'Estrutura do plano atualizada com sucesso.');
    }

    public function destroy(EstruturaPlano $estruturas_plano)
    {
        $estruturas_plano->delete();

        return redirect()->route('academico.estruturas-plano.index')
            ->with('success', 'Estrutura do plano removida.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'topicos' => 'nullable|array',
            'topicos.*' => 'exists:topicos_plano,id',
        ]);
    }

    private function sincronizarTopicos(EstruturaPlano $estrutura, array $topicos): void
    {
        $sync = [];
        foreach (array_values($topicos) as $i => $topicoId) {
            $sync[$topicoId] = ['ordem' => $i];
        }
        $estrutura->topicos()->sync($sync);
    }
}
