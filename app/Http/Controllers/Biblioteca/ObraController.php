<?php

namespace App\Http\Controllers\Biblioteca;

use App\Http\Controllers\Controller;
use App\Models\AreaConhecimento;
use App\Models\Autor;
use App\Models\Colecao;
use App\Models\Editor;
use App\Models\Idioma;
use App\Models\Obra;
use App\Models\TipoMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ObraController extends Controller
{
    public function index()
    {
        $obras = Obra::with('editor', 'tipoMaterial', 'autores')->withCount('exemplares')->orderBy('titulo')->paginate(20);

        return view('biblioteca.obras.index', compact('obras'));
    }

    public function create()
    {
        return view('biblioteca.obras.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $data['capa'] = $this->salvarCapa($request);

        $obra = Obra::create($data);
        $obra->autores()->sync($request->input('autores', []));

        return redirect()->route('biblioteca.obras.index')->with('success', 'Obra cadastrada com sucesso.');
    }

    public function edit(Obra $obra)
    {
        return view('biblioteca.obras.form', $this->dados($obra->load('autores')));
    }

    public function update(Request $request, Obra $obra)
    {
        $data = $this->validar($request);
        if ($request->hasFile('capa')) {
            $data['capa'] = $this->salvarCapa($request);
        }

        $obra->update($data);
        $obra->autores()->sync($request->input('autores', []));

        return redirect()->route('biblioteca.obras.index')->with('success', 'Obra atualizada com sucesso.');
    }

    public function destroy(Obra $obra)
    {
        $obra->delete();

        return redirect()->route('biblioteca.obras.index')->with('success', 'Obra removida.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'isbn' => 'nullable|string|max:255',
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'nullable|string|max:255',
            'editor_id' => 'nullable|exists:editores,id',
            'area_conhecimento_id' => 'nullable|exists:areas_conhecimento,id',
            'idioma_id' => 'nullable|exists:idiomas,id',
            'tipo_material_id' => 'nullable|exists:tipos_material,id',
            'colecao_id' => 'nullable|exists:colecoes,id',
            'capa' => 'nullable|image|max:4096',
            'autores' => 'nullable|array',
            'autores.*' => 'exists:autores,id',
        ]);
    }

    private function salvarCapa(Request $request): ?string
    {
        if ($request->hasFile('capa')) {
            return $request->file('capa')->store('biblioteca/capas', 'public');
        }
        return null;
    }

    private function dados(?Obra $obra): array
    {
        return [
            'obra' => $obra,
            'editores' => Editor::orderBy('nome')->get(),
            'areas' => AreaConhecimento::orderBy('nome')->get(),
            'idiomas' => Idioma::orderBy('nome')->get(),
            'tiposMaterial' => TipoMaterial::orderBy('nome')->get(),
            'colecoes' => Colecao::orderBy('nome')->get(),
            'autores' => Autor::orderBy('nome')->get(),
        ];
    }
}
