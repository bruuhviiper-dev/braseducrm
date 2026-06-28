<?php

namespace App\Http\Controllers\Ged;

use App\Http\Controllers\Controller;
use App\Models\AtoRegulatorio;
use App\Models\Curso;
use Illuminate\Http\Request;

class AtoRegulatorioController extends Controller
{
    public function index()
    {
        $atos = AtoRegulatorio::with('curso')->orderBy('data_publicacao', 'desc')->paginate(20);
        return view('ged.atos.index', compact('atos'));
    }

    public function create()
    {
        $cursos = Curso::orderBy('nome')->get();
        return view('ged.atos.form', compact('cursos'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo', true);
        AtoRegulatorio::create($data);
        return redirect()->route('ged.atos.index')->with('success', 'Ato regulatório criado com sucesso.');
    }

    public function edit(AtoRegulatorio $ato)
    {
        $cursos = Curso::orderBy('nome')->get();
        return view('ged.atos.form', compact('ato', 'cursos'));
    }

    public function update(Request $request, AtoRegulatorio $ato)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo');
        $ato->update($data);
        return redirect()->route('ged.atos.index')->with('success', 'Ato regulatório atualizado com sucesso.');
    }

    public function destroy(AtoRegulatorio $ato)
    {
        $ato->delete();
        return redirect()->route('ged.atos.index')->with('success', 'Ato regulatório removido com sucesso.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'tipo' => 'required|in:credenciamento,recredenciamento,autorizacao,reconhecimento,renovacao,outro',
            'numero' => 'nullable|string|max:255',
            'curso_id' => 'nullable|exists:cursos,id',
            'data_publicacao' => 'nullable|date',
            'validade' => 'nullable|date',
            'orgao' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string',
        ]);
    }
}
