<?php

namespace App\Http\Controllers\Ead;

use App\Http\Controllers\Controller;
use App\Models\AgrupadorCurso;
use App\Models\SubAgrupadorCurso;
use Illuminate\Http\Request;

class SubAgrupadorCursoController extends Controller
{
    public function index()
    {
        $registros = SubAgrupadorCurso::with('agrupador')->orderBy('nome')->paginate(20);

        return view('ead.sub-agrupadores.index', compact('registros'));
    }

    public function create()
    {
        return view('ead.sub-agrupadores.form', ['registro' => null, 'agrupadores' => AgrupadorCurso::orderBy('nome')->get()]);
    }

    public function store(Request $request)
    {
        SubAgrupadorCurso::create($this->validar($request));

        return redirect()->route('ead.sub-agrupadores.index')->with('success', 'Sub agrupador criado com sucesso.');
    }

    public function edit(SubAgrupadorCurso $sub_agrupadore)
    {
        return view('ead.sub-agrupadores.form', ['registro' => $sub_agrupadore, 'agrupadores' => AgrupadorCurso::orderBy('nome')->get()]);
    }

    public function update(Request $request, SubAgrupadorCurso $sub_agrupadore)
    {
        $sub_agrupadore->update($this->validar($request));

        return redirect()->route('ead.sub-agrupadores.index')->with('success', 'Sub agrupador atualizado.');
    }

    public function destroy(SubAgrupadorCurso $sub_agrupadore)
    {
        $sub_agrupadore->delete();

        return redirect()->route('ead.sub-agrupadores.index')->with('success', 'Sub agrupador removido.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'agrupador_curso_id' => 'nullable|exists:agrupadores_curso,id',
        ]);
    }
}
