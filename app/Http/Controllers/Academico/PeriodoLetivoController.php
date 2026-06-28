<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\PeriodoLetivo;
use Illuminate\Http\Request;

class PeriodoLetivoController extends Controller
{
    public function index()
    {
        $periodos = PeriodoLetivo::orderBy('nome')->paginate(15);

        return view('academico.periodos-letivos.index', compact('periodos'));
    }

    public function create()
    {
        return view('academico.periodos-letivos.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
        ]);

        $data = $request->all();
        $data['ativo'] = $request->has('ativo');

        PeriodoLetivo::create($data);

        return redirect()->route('academico.periodos-letivos.index')
            ->with('success', 'Periodo letivo cadastrado com sucesso.');
    }

    public function edit(PeriodoLetivo $periodos_letivo)
    {
        $periodo = $periodos_letivo;

        return view('academico.periodos-letivos.form', compact('periodo'));
    }

    public function update(Request $request, PeriodoLetivo $periodos_letivo)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
        ]);

        $data = $request->all();
        $data['ativo'] = $request->has('ativo');

        $periodos_letivo->update($data);

        return redirect()->route('academico.periodos-letivos.index')
            ->with('success', 'Periodo letivo atualizado com sucesso.');
    }

    public function destroy(PeriodoLetivo $periodos_letivo)
    {
        $periodos_letivo->delete();

        return redirect()->route('academico.periodos-letivos.index')
            ->with('success', 'Periodo letivo removido com sucesso.');
    }
}
