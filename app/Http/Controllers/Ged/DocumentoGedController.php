<?php

namespace App\Http\Controllers\Ged;

use App\Http\Controllers\Controller;
use App\Models\DocumentoGed;
use App\Models\ClassificacaoGed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoGedController extends Controller
{
    public function index()
    {
        $documentos = DocumentoGed::with('classificacao')->orderBy('id', 'desc')->paginate(20);
        return view('ged.documentos.index', compact('documentos'));
    }

    public function create()
    {
        $classificacoes = ClassificacaoGed::where('ativo', true)->orderBy('nome')->get();
        return view('ged.documentos.form', compact('classificacoes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'classificacao_ged_id' => 'nullable|exists:classificacoes_ged,id',
            'titulo' => 'required|string|max:255',
            'observacoes' => 'nullable|string',
            'arquivo' => 'required|file|max:20480', // 20 MB
        ]);

        $file = $request->file('arquivo');
        $data['arquivo'] = $file->store('ged', 'public');
        $data['tipo_arquivo'] = $file->getClientOriginalExtension();
        $data['enviado_por'] = auth()->id();

        DocumentoGed::create($data);
        return redirect()->route('ged.documentos.index')->with('success', 'Documento enviado com sucesso.');
    }

    public function edit(DocumentoGed $documento)
    {
        $classificacoes = ClassificacaoGed::where('ativo', true)->orderBy('nome')->get();
        return view('ged.documentos.form', compact('documento', 'classificacoes'));
    }

    public function update(Request $request, DocumentoGed $documento)
    {
        $data = $request->validate([
            'classificacao_ged_id' => 'nullable|exists:classificacoes_ged,id',
            'titulo' => 'required|string|max:255',
            'observacoes' => 'nullable|string',
            'arquivo' => 'nullable|file|max:20480',
        ]);

        if ($request->hasFile('arquivo')) {
            if ($documento->arquivo) {
                Storage::disk('public')->delete($documento->arquivo);
            }
            $file = $request->file('arquivo');
            $data['arquivo'] = $file->store('ged', 'public');
            $data['tipo_arquivo'] = $file->getClientOriginalExtension();
        } else {
            unset($data['arquivo']);
        }

        $documento->update($data);
        return redirect()->route('ged.documentos.index')->with('success', 'Documento atualizado com sucesso.');
    }

    public function destroy(DocumentoGed $documento)
    {
        if ($documento->arquivo) {
            Storage::disk('public')->delete($documento->arquivo);
        }
        $documento->delete();
        return redirect()->route('ged.documentos.index')->with('success', 'Documento removido com sucesso.');
    }
}
