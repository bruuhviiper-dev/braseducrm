<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\MetaCrm;
use App\Models\Funil;
use App\Models\User;
use Illuminate\Http\Request;

class MetaCrmController extends Controller
{
    public function index()
    {
        $metas = MetaCrm::with(['funil', 'consultor'])->orderBy('data_inicio', 'desc')->paginate(20);
        return view('crm.metas.index', compact('metas'));
    }

    public function create()
    {
        $funis = Funil::where('ativo', true)->orderBy('nome')->get();
        $consultores = User::orderBy('nome')->get();
        return view('crm.metas.form', compact('funis', 'consultores'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        MetaCrm::create($data);
        return redirect()->route('crm.metas.index')->with('success', 'Meta criada com sucesso.');
    }

    public function edit(MetaCrm $meta)
    {
        $funis = Funil::where('ativo', true)->orderBy('nome')->get();
        $consultores = User::orderBy('nome')->get();
        return view('crm.metas.form', compact('meta', 'funis', 'consultores'));
    }

    public function update(Request $request, MetaCrm $meta)
    {
        $data = $this->validateData($request);
        $meta->update($data);
        return redirect()->route('crm.metas.index')->with('success', 'Meta atualizada com sucesso.');
    }

    public function destroy(MetaCrm $meta)
    {
        $meta->delete();
        return redirect()->route('crm.metas.index')->with('success', 'Meta removida com sucesso.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'funil_id' => 'nullable|exists:funis,id',
            'consultor_id' => 'nullable|exists:users,id',
            'tipo' => 'required|in:quantidade,valor',
            'periodo' => 'required|in:semanal,mensal',
            'meta_valor' => 'required|numeric|min:0',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);
    }
}
