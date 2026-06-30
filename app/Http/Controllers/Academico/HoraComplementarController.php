<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\HoraComplementar;
use App\Models\Matricula;
use Illuminate\Http\Request;

class HoraComplementarController extends Controller
{
    public function index()
    {
        $registros = HoraComplementar::with('matricula.aluno.pessoa')->orderByDesc('id')->paginate(20);

        return view('academico.horas-complementares.index', compact('registros'));
    }

    public function create()
    {
        return view('academico.horas-complementares.form', [
            'registro' => null,
            'matriculas' => $this->matriculas(),
        ]);
    }

    public function store(Request $request)
    {
        HoraComplementar::create($this->validar($request));

        return redirect()->route('academico.horas-complementares.index')
            ->with('success', 'Horas complementares lançadas com sucesso.');
    }

    public function edit(HoraComplementar $horas_complementare)
    {
        return view('academico.horas-complementares.form', [
            'registro' => $horas_complementare,
            'matriculas' => $this->matriculas(),
        ]);
    }

    public function update(Request $request, HoraComplementar $horas_complementare)
    {
        $horas_complementare->update($this->validar($request));

        return redirect()->route('academico.horas-complementares.index')
            ->with('success', 'Registro atualizado com sucesso.');
    }

    public function destroy(HoraComplementar $horas_complementare)
    {
        $horas_complementare->delete();

        return redirect()->route('academico.horas-complementares.index')
            ->with('success', 'Registro removido.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'matricula_id' => 'required|exists:matriculas,id',
            'tipo' => 'required|in:' . implode(',', HoraComplementar::TIPOS),
            'quantidade' => 'required|numeric|min:0',
            'situacao' => 'required|in:' . implode(',', HoraComplementar::SITUACOES),
            'descricao' => 'nullable|string',
        ]);
    }

    private function matriculas()
    {
        return Matricula::with('aluno.pessoa')->orderByDesc('id')->get();
    }
}
