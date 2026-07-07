<?php

namespace App\Http\Controllers\MatriculaOnline;

use App\Http\Controllers\Controller;
use App\Models\CupomDesconto;
use App\Models\AberturaMatriculaOnline;
use Illuminate\Http\Request;

class CupomController extends Controller
{
    public function index()
    {
        $cupons = CupomDesconto::with('abertura')->orderBy('id', 'desc')->paginate(20);
        return view('matricula-online.cupons.index', compact('cupons'));
    }

    public function create()
    {
        $aberturas = AberturaMatriculaOnline::orderBy('nome')->get();
        $consultores = \App\Models\User::where('ativo', true)->orderBy('nome')->get();
        return view('matricula-online.cupons.form', compact('aberturas', 'consultores'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request, null);
        $data['ativo'] = $request->boolean('ativo', true);
        CupomDesconto::create($data);
        return redirect()->route('matricula-online.cupons.index')->with('success', 'Cupom criado com sucesso.');
    }

    public function edit(CupomDesconto $cupom)
    {
        $aberturas = AberturaMatriculaOnline::orderBy('nome')->get();
        $consultores = \App\Models\User::where('ativo', true)->orderBy('nome')->get();
        return view('matricula-online.cupons.form', compact('cupom', 'aberturas', 'consultores'));
    }

    public function update(Request $request, CupomDesconto $cupom)
    {
        $data = $this->validateData($request, $cupom->id);
        $data['ativo'] = $request->boolean('ativo');
        $cupom->update($data);
        return redirect()->route('matricula-online.cupons.index')->with('success', 'Cupom atualizado com sucesso.');
    }

    public function destroy(CupomDesconto $cupom)
    {
        $cupom->delete();
        return redirect()->route('matricula-online.cupons.index')->with('success', 'Cupom removido com sucesso.');
    }

    private function validateData(Request $request, ?int $id): array
    {
        return $request->validate([
            'codigo' => 'required|string|max:255|unique:cupons_desconto,codigo' . ($id ? ',' . $id : ''),
            'tipo' => 'required|in:percentual,valor',
            'valor' => 'required|numeric|min:0',
            'quantidade_total' => 'nullable|integer|min:0',
            'validade' => 'nullable|date',
            'abertura_matricula_id' => 'nullable|exists:aberturas_matricula_online,id',
            // EDUQ: o cupom pode incidir só na taxa de matrícula, só nas mensalidades ou em ambas
            'incidencia' => 'required|in:matricula,mensalidades,ambas',
            // e pode ser exclusivo de um consultor comercial
            'consultor_id' => 'nullable|exists:users,id',
        ]);
    }
}
