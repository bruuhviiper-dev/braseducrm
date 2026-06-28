<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Pessoa;
use App\Models\FormaIngresso;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    public function index(Request $request)
    {
        $query = Aluno::with(['pessoa', 'formaIngresso']);

        if ($search = $request->get('search')) {
            $query->whereHas('pessoa', function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%");
            })->orWhere('ra', 'like', "%{$search}%");
        }

        $alunos = $query->orderByDesc('id')->paginate(15)->withQueryString();

        return view('alunos.index', compact('alunos'));
    }

    public function create()
    {
        $formasIngresso = FormaIngresso::orderBy('nome')->get();
        $pessoas = Pessoa::orderBy('nome')->get();

        return view('alunos.form', compact('formasIngresso', 'pessoas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'ra' => 'nullable|string|max:50',
            'forma_ingresso_id' => 'nullable|exists:formas_ingresso,id',
            'data_ingresso' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['ativo'] = $request->has('ativo');

        Aluno::create($data);

        return redirect()->route('alunos.index')
            ->with('success', 'Aluno cadastrado com sucesso.');
    }

    public function edit(Aluno $aluno)
    {
        $aluno->load('pessoa');
        $formasIngresso = FormaIngresso::orderBy('nome')->get();
        $pessoas = Pessoa::orderBy('nome')->get();

        return view('alunos.form', compact('aluno', 'formasIngresso', 'pessoas'));
    }

    public function update(Request $request, Aluno $aluno)
    {
        $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'ra' => 'nullable|string|max:50',
            'forma_ingresso_id' => 'nullable|exists:formas_ingresso,id',
            'data_ingresso' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['ativo'] = $request->has('ativo');

        $aluno->update($data);

        return redirect()->route('alunos.index')
            ->with('success', 'Aluno atualizado com sucesso.');
    }

    public function destroy(Aluno $aluno)
    {
        $aluno->delete();

        return redirect()->route('alunos.index')
            ->with('success', 'Aluno removido com sucesso.');
    }
}
