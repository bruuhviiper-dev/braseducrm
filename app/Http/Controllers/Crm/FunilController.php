<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Funil;
use App\Models\EtapaFunil;
use Illuminate\Http\Request;

class FunilController extends Controller
{
    public function index()
    {
        $funis = Funil::withCount(['etapas', 'oportunidades'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('crm.funil.index', compact('funis'));
    }

    public function create()
    {
        return view('crm.funil.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'padrao' => 'boolean',
            'ativo' => 'boolean',
            'etapas' => 'nullable|array',
            'etapas.*.nome' => 'required|string|max:255',
            'etapas.*.cor' => 'required|string|max:7',
            'etapas.*.ordem' => 'required|integer|min:1',
            'etapas.*.prazo_dias' => 'nullable|integer|min:0',
        ]);

        $funil = Funil::create([
            'nome' => $validated['nome'],
            'padrao' => $request->boolean('padrao'),
            'ativo' => $request->boolean('ativo', true),
        ]);

        if (!empty($validated['etapas'])) {
            foreach ($validated['etapas'] as $etapaData) {
                $funil->etapas()->create($etapaData);
            }
        }

        return redirect()->route('crm.funil.index')
            ->with('success', 'Funil criado com sucesso.');
    }

    /**
     * Funil de Vendas 110 (doc CRM): Kanban com etapas do funil + colunas fixas
     * GANHO e PERDA. O filtro Situação (padrão "Em Andamento") oculta os negócios
     * já ganhos/perdidos para não poluir a tela.
     */
    public function show(Request $request, Funil $funil)
    {
        $situacao = $request->query('situacao', 'andamento'); // andamento|ganho|perda|todas
        $rel = ['interessado', 'consultor', 'tags', 'curso', 'origem', 'historicos', 'atividades'];

        $funil->load(['etapas' => function ($query) use ($rel) {
            $query->orderBy('ordem')->with(['oportunidades' => function ($q) use ($rel) {
                $q->whereIn('situacao', ['aberta', 'pausada'])->with($rel)->orderByDesc('id');
            }]);
        }]);

        $ganhas = in_array($situacao, ['ganho', 'todas'])
            ? $funil->oportunidades()->where('situacao', 'ganha')->with($rel)->orderByDesc('id')->get()
            : collect();
        $perdidas = in_array($situacao, ['perda', 'todas'])
            ? $funil->oportunidades()->where('situacao', 'perdida')->with($rel)->orderByDesc('id')->get()
            : collect();
        $totais = [
            'ganha' => $funil->oportunidades()->where('situacao', 'ganha')->count(),
            'perdida' => $funil->oportunidades()->where('situacao', 'perdida')->count(),
        ];
        $funis = Funil::where('ativo', true)->orderBy('nome')->get();
        $motivosPerda = \App\Models\MotivoPerda::orderBy('nome')->get();

        return view('crm.funil.show', compact('funil', 'funis', 'situacao', 'ganhas', 'perdidas', 'totais', 'motivosPerda'));
    }

    public function edit(Funil $funil)
    {
        $funil->load(['etapas' => function ($query) {
            $query->orderBy('ordem');
        }]);

        return view('crm.funil.form', compact('funil'));
    }

    public function update(Request $request, Funil $funil)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'padrao' => 'boolean',
            'ativo' => 'boolean',
            'etapas' => 'nullable|array',
            'etapas.*.id' => 'nullable|exists:etapas_funil,id',
            'etapas.*.nome' => 'required|string|max:255',
            'etapas.*.cor' => 'required|string|max:7',
            'etapas.*.ordem' => 'required|integer|min:1',
            'etapas.*.prazo_dias' => 'nullable|integer|min:0',
        ]);

        $funil->update([
            'nome' => $validated['nome'],
            'padrao' => $request->boolean('padrao'),
            'ativo' => $request->boolean('ativo', true),
        ]);

        // Sync etapas
        $existingIds = [];
        if (!empty($validated['etapas'])) {
            foreach ($validated['etapas'] as $etapaData) {
                if (!empty($etapaData['id'])) {
                    $etapa = EtapaFunil::find($etapaData['id']);
                    if ($etapa && $etapa->funil_id === $funil->id) {
                        $etapa->update($etapaData);
                        $existingIds[] = $etapa->id;
                    }
                } else {
                    $etapa = $funil->etapas()->create($etapaData);
                    $existingIds[] = $etapa->id;
                }
            }
        }

        // Remove etapas that were deleted in the form
        $funil->etapas()->whereNotIn('id', $existingIds)->delete();

        return redirect()->route('crm.funil.index')
            ->with('success', 'Funil atualizado com sucesso.');
    }

    public function destroy(Funil $funil)
    {
        $funil->etapas()->delete();
        $funil->delete();

        return redirect()->route('crm.funil.index')
            ->with('success', 'Funil removido com sucesso.');
    }
}
