<?php

namespace App\Http\Controllers\MatriculaOnline;

use App\Http\Controllers\Controller;
use App\Models\CupomPersonalizado;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CupomPersonalizadoController extends Controller
{
    public function index()
    {
        $cupons = CupomPersonalizado::orderByDesc('id')->paginate(20);

        return view('matricula-online.cupons-personalizados.index', compact('cupons'));
    }

    public function create()
    {
        return view('matricula-online.cupons-personalizados.form', ['cupom' => null, 'sugestao' => $this->gerarCodigo()]);
    }

    public function store(Request $request)
    {
        CupomPersonalizado::create($this->validar($request));

        return redirect()->route('matricula-online.cupons-personalizados.index')->with('success', 'Cupom personalizado criado.');
    }

    public function edit(CupomPersonalizado $cupons_personalizado)
    {
        return view('matricula-online.cupons-personalizados.form', ['cupom' => $cupons_personalizado, 'sugestao' => $cupons_personalizado->codigo]);
    }

    public function update(Request $request, CupomPersonalizado $cupons_personalizado)
    {
        $cupons_personalizado->update($this->validar($request, $cupons_personalizado->id));

        return redirect()->route('matricula-online.cupons-personalizados.index')->with('success', 'Cupom atualizado.');
    }

    public function destroy(CupomPersonalizado $cupons_personalizado)
    {
        $cupons_personalizado->delete();

        return redirect()->route('matricula-online.cupons-personalizados.index')->with('success', 'Cupom removido.');
    }

    private function validar(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:255|unique:cupons_personalizados,codigo' . ($id ? ',' . $id : ''),
            'beneficiario' => 'nullable|string|max:255',
            'tipo_desconto' => 'required|in:' . implode(',', array_keys(CupomPersonalizado::TIPOS)),
            'valor_desconto' => 'required|numeric|min:0',
            'validade' => 'nullable|date',
        ]);
        $data['usado'] = $request->boolean('usado');
        $data['ativo'] = $request->boolean('ativo');

        return $data;
    }

    private function gerarCodigo(): string
    {
        return strtoupper(Str::random(8));
    }
}
