<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Oportunidade;
use App\Models\Interessado;
use App\Models\Funil;
use App\Models\EtapaFunil;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Request;

class OportunidadeController extends Controller
{
    public function index()
    {
        $oportunidades = Oportunidade::with(['interessado', 'funil', 'etapaFunil', 'consultor', 'curso'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('crm.oportunidades.index', compact('oportunidades'));
    }

    public function create()
    {
        $interessados = Interessado::where('ativo', true)->orderBy('nome')->get();
        $funis = Funil::where('ativo', true)->orderBy('nome')->get();
        $etapas = EtapaFunil::orderBy('ordem')->get();
        $consultores = User::where('ativo', true)->orderBy('nome')->get();
        $cursos = Curso::where('ativo', true)->orderBy('nome')->get();

        return view('crm.oportunidades.form', compact('interessados', 'funis', 'etapas', 'consultores', 'cursos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'interessado_id' => 'required|exists:interessados,id',
            'funil_id' => 'required|exists:funis,id',
            'etapa_funil_id' => 'required|exists:etapas_funil,id',
            'consultor_id' => 'nullable|exists:users,id',
            'curso_id' => 'nullable|exists:cursos,id',
            'titulo' => 'nullable|string|max:255',
            'valor' => 'nullable|numeric|min:0',
            'situacao' => 'required|in:aberta,ganha,perdida,pausada',
            'data_previsao_fechamento' => 'nullable|date',
            'observacoes' => 'nullable|string',
        ]);

        Oportunidade::create($validated);

        return redirect()->route('crm.oportunidades.index')
            ->with('success', 'Oportunidade criada com sucesso.');
    }

    public function edit(Oportunidade $oportunidade)
    {
        $interessados = Interessado::where('ativo', true)->orderBy('nome')->get();
        $funis = Funil::where('ativo', true)->orderBy('nome')->get();
        $etapas = EtapaFunil::orderBy('ordem')->get();
        $consultores = User::where('ativo', true)->orderBy('nome')->get();
        $cursos = Curso::where('ativo', true)->orderBy('nome')->get();

        return view('crm.oportunidades.form', compact('oportunidade', 'interessados', 'funis', 'etapas', 'consultores', 'cursos'));
    }

    public function update(Request $request, Oportunidade $oportunidade)
    {
        $validated = $request->validate([
            'interessado_id' => 'required|exists:interessados,id',
            'funil_id' => 'required|exists:funis,id',
            'etapa_funil_id' => 'required|exists:etapas_funil,id',
            'consultor_id' => 'nullable|exists:users,id',
            'curso_id' => 'nullable|exists:cursos,id',
            'titulo' => 'nullable|string|max:255',
            'valor' => 'nullable|numeric|min:0',
            'situacao' => 'required|in:aberta,ganha,perdida,pausada',
            'data_previsao_fechamento' => 'nullable|date',
            'observacoes' => 'nullable|string',
        ]);

        $oportunidade->update($validated);

        return redirect()->route('crm.oportunidades.index')
            ->with('success', 'Oportunidade atualizada com sucesso.');
    }

    public function destroy(Oportunidade $oportunidade)
    {
        $oportunidade->delete();

        return redirect()->route('crm.oportunidades.index')
            ->with('success', 'Oportunidade removida com sucesso.');
    }

    public function moverEtapa(Request $request, Oportunidade $oportunidade)
    {
        $validated = $request->validate([
            'etapa_funil_id' => 'required|exists:etapas_funil,id',
        ]);

        $oportunidade->update(['etapa_funil_id' => $validated['etapa_funil_id']]);

        return response()->json(['success' => true]);
    }
}
