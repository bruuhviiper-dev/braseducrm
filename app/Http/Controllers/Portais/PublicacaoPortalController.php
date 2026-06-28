<?php

namespace App\Http\Controllers\Portais;

use App\Http\Controllers\Controller;
use App\Models\PublicacaoPortal;
use App\Models\PastaPortal;
use Illuminate\Http\Request;

class PublicacaoPortalController extends Controller
{
    public function index()
    {
        $publicacoes = PublicacaoPortal::with('pasta')->orderBy('id', 'desc')->paginate(20);
        return view('portais.publicacoes.index', compact('publicacoes'));
    }

    public function create()
    {
        $pastas = PastaPortal::where('ativo', true)->orderBy('nome')->get();
        return view('portais.publicacoes.form', compact('pastas'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo', true);
        $data['publicado_por'] = auth()->id();
        PublicacaoPortal::create($data);
        return redirect()->route('portais.publicacoes.index')->with('success', 'Publicação criada com sucesso.');
    }

    public function edit(PublicacaoPortal $publicaco)
    {
        $publicacao = $publicaco;
        $pastas = PastaPortal::where('ativo', true)->orderBy('nome')->get();
        return view('portais.publicacoes.form', compact('publicacao', 'pastas'));
    }

    public function update(Request $request, PublicacaoPortal $publicaco)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo');
        $publicaco->update($data);
        return redirect()->route('portais.publicacoes.index')->with('success', 'Publicação atualizada com sucesso.');
    }

    public function destroy(PublicacaoPortal $publicaco)
    {
        $publicaco->delete();
        return redirect()->route('portais.publicacoes.index')->with('success', 'Publicação removida com sucesso.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'pasta_portal_id' => 'nullable|exists:pastas_portal,id',
            'titulo' => 'required|string|max:255',
            'conteudo' => 'nullable|string',
            'publicado_em' => 'nullable|date',
        ]);
    }
}
