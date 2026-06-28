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

    public function show(Funil $funil)
    {
        $funil->load(['etapas' => function ($query) {
            $query->orderBy('ordem')->with(['oportunidades' => function ($q) {
                $q->with(['interessado', 'consultor']);
            }]);
        }]);

        return view('crm.funil.show', compact('funil'));
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
