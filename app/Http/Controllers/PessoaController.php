<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use App\Models\Religiao;
use App\Models\Profissao;
use App\Models\Escola;
use Illuminate\Http\Request;

class PessoaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pessoa::with(['aluno', 'profissional']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pessoas = $query->orderBy('nome')->paginate(15)->withQueryString();

        return view('pessoas.index', compact('pessoas'));
    }

    public function create()
    {
        $religioes = Religiao::orderBy('nome')->get();
        $profissoes = Profissao::orderBy('nome')->get();
        $escolas = Escola::orderBy('nome')->get();

        return view('pessoas.form', compact('religioes', 'profissoes', 'escolas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:fisica,juridica',
            'nome' => 'required|string|max:255',
            'cpf' => 'nullable|string|max:14|unique:pessoas,cpf',
            'cnpj' => 'nullable|string|max:18',
            'email' => 'nullable|email|max:255',
            'data_nascimento' => 'nullable|date',
            'cep' => 'nullable|string|max:10',
            'uf' => 'nullable|string|max:2',
        ]);

        $data = $request->all();
        $data['ativo'] = $request->has('ativo');

        Pessoa::create($data);

        return redirect()->route('pessoas.index')
            ->with('success', 'Pessoa cadastrada com sucesso.');
    }

    public function show(Pessoa $pessoa)
    {
        $pessoa->load(['religiao', 'profissao', 'escola', 'aluno.formaIngresso', 'profissional']);

        return view('pessoas.show', compact('pessoa'));
    }

    public function edit(Pessoa $pessoa)
    {
        $religioes = Religiao::orderBy('nome')->get();
        $profissoes = Profissao::orderBy('nome')->get();
        $escolas = Escola::orderBy('nome')->get();

        return view('pessoas.form', compact('pessoa', 'religioes', 'profissoes', 'escolas'));
    }

    public function update(Request $request, Pessoa $pessoa)
    {
        $request->validate([
            'tipo' => 'required|in:fisica,juridica',
            'nome' => 'required|string|max:255',
            'cpf' => 'nullable|string|max:14|unique:pessoas,cpf,' . $pessoa->id,
            'cnpj' => 'nullable|string|max:18',
            'email' => 'nullable|email|max:255',
            'data_nascimento' => 'nullable|date',
            'cep' => 'nullable|string|max:10',
            'uf' => 'nullable|string|max:2',
        ]);

        $data = $request->all();
        $data['ativo'] = $request->has('ativo');

        $pessoa->update($data);

        return redirect()->route('pessoas.index')
            ->with('success', 'Pessoa atualizada com sucesso.');
    }

    public function destroy(Pessoa $pessoa)
    {
        $pessoa->delete();

        return redirect()->route('pessoas.index')
            ->with('success', 'Pessoa removida com sucesso.');
    }
}
