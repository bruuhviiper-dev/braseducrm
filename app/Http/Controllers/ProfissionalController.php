<?php

namespace App\Http\Controllers;

use App\Models\Profissional;
use App\Models\Pessoa;
use App\Models\TipoProfissional;
use App\Models\Titularidade;
use Illuminate\Http\Request;

class ProfissionalController extends Controller
{
    public function index()
    {
        $profissionais = Profissional::with(['pessoa', 'tipoProfissional', 'titularidade'])
            ->orderBy('id', 'desc')->paginate(20);
        return view('profissionais.index', compact('profissionais'));
    }

    public function create()
    {
        return view('profissionais.form', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo', true);
        Profissional::create($data);
        return redirect()->route('profissionais.index')->with('success', 'Profissional criado com sucesso.');
    }

    public function edit(Profissional $profissional)
    {
        return view('profissionais.form', array_merge($this->formData(), compact('profissional')));
    }

    public function update(Request $request, Profissional $profissional)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo');
        $profissional->update($data);
        return redirect()->route('profissionais.index')->with('success', 'Profissional atualizado com sucesso.');
    }

    public function destroy(Profissional $profissional)
    {
        $profissional->delete();
        return redirect()->route('profissionais.index')->with('success', 'Profissional removido com sucesso.');
    }

    private function formData(): array
    {
        return [
            'pessoas' => Pessoa::orderBy('nome')->get(),
            'tipos' => TipoProfissional::orderBy('nome')->get(),
            'titularidades' => Titularidade::orderBy('nome')->get(),
        ];
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'tipo_profissional_id' => 'nullable|exists:tipos_profissional,id',
            'titularidade_id' => 'nullable|exists:titularidades,id',
            'registro_profissional' => 'nullable|string|max:255',
        ]);
    }
}
