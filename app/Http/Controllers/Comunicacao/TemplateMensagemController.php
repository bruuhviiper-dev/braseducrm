<?php

namespace App\Http\Controllers\Comunicacao;

use App\Http\Controllers\Controller;
use App\Models\TemplateMensagem;
use Illuminate\Http\Request;

class TemplateMensagemController extends Controller
{
    public function index()
    {
        $templates = TemplateMensagem::orderBy('nome')->paginate(20);
        return view('comunicacao.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('comunicacao.templates.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:vencimento,cobranca,interessados,pagamento,avulsa',
            'canal' => 'required|string|max:20',
            'assunto' => 'nullable|string|max:255',
            'conteudo' => 'required|string',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');
        TemplateMensagem::create($data);
        return redirect()->route('comunicacao.templates.index')->with('success', 'Template criado com sucesso.');
    }

    public function edit(TemplateMensagem $template)
    {
        return view('comunicacao.templates.form', compact('template'));
    }

    public function update(Request $request, TemplateMensagem $template)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:vencimento,cobranca,interessados,pagamento,avulsa',
            'canal' => 'required|string|max:20',
            'assunto' => 'nullable|string|max:255',
            'conteudo' => 'required|string',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');
        $template->update($data);
        return redirect()->route('comunicacao.templates.index')->with('success', 'Template atualizado com sucesso.');
    }

    public function destroy(TemplateMensagem $template)
    {
        $template->delete();
        return redirect()->route('comunicacao.templates.index')->with('success', 'Template removido com sucesso.');
    }
}
