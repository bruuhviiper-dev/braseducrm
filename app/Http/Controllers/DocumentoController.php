<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Curso;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::with('curso')->orderBy('nome')->paginate(20);
        return view('administrativo.documentos.index', compact('documentos'));
    }

    public function create()
    {
        $cursos = Curso::where('ativo', true)->orderBy('nome')->get();
        $formasIngresso = \App\Models\FormaIngresso::orderBy('nome')->get();
        return view('administrativo.documentos.form', compact('cursos', 'formasIngresso'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['obrigatorio'] = $request->boolean('obrigatorio');
        $data['ativo'] = $request->boolean('ativo', true);
        Documento::create($data);
        return redirect()->route('documentos.index')->with('success', 'Documento criado com sucesso.');
    }

    public function edit(Documento $documento)
    {
        $cursos = Curso::where('ativo', true)->orderBy('nome')->get();
        $formasIngresso = \App\Models\FormaIngresso::orderBy('nome')->get();
        return view('administrativo.documentos.form', compact('documento', 'cursos', 'formasIngresso'));
    }

    public function update(Request $request, Documento $documento)
    {
        $data = $this->validateData($request);
        $data['obrigatorio'] = $request->boolean('obrigatorio');
        $data['ativo'] = $request->boolean('ativo');
        $documento->update($data);
        return redirect()->route('documentos.index')->with('success', 'Documento atualizado com sucesso.');
    }

    public function destroy(Documento $documento)
    {
        $documento->delete();
        return redirect()->route('documentos.index')->with('success', 'Documento removido com sucesso.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'sigla' => 'nullable|string|max:20',
            'nome' => 'required|string|max:255',
            'tipo_ged' => 'nullable|string|max:255',
            'idade_minima' => 'nullable|integer|min:0|max:120',
            'visibilidade_matriz' => 'nullable|boolean',
            'obrigatorio_generos' => 'nullable|boolean',
            'grau' => 'nullable|string|max:255',
            'curso_id' => 'nullable|exists:cursos,id',
            'forma_ingresso_id' => 'nullable|exists:formas_ingresso,id',
        ]);
    }
}
