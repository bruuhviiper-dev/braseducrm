<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Interessado;
use App\Models\OrigemInteressado;
use App\Models\CategoriaInteressado;
use App\Models\Curso;
use App\Services\RdStationService;
use Illuminate\Http\Request;

class InteressadoController extends Controller
{
    public function index()
    {
        $interessados = Interessado::with(['pessoa', 'origemInteressado', 'categoriaInteressado', 'curso'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('crm.interessados.index', compact('interessados'));
    }

    public function create()
    {
        $origens = OrigemInteressado::where('ativo', true)->orderBy('nome')->get();
        $categorias = CategoriaInteressado::orderBy('nome')->get();
        $cursos = Curso::where('ativo', true)->orderBy('nome')->get();

        return view('crm.interessados.form', compact('origens', 'categorias', 'cursos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
            'origem_id' => 'nullable|exists:origens_interessado,id',
            'categoria_id' => 'nullable|exists:categorias_interessado,id',
            'curso_id' => 'nullable|exists:cursos,id',
            'observacoes' => 'nullable|string',
        ]);

        $validated['ativo'] = true;

        $interessado = Interessado::create($validated);

        // Envia o lead ao RD Station, se a integração estiver ativa.
        (new RdStationService())->enviarLead($interessado);

        return redirect()->route('crm.interessados.index')
            ->with('success', 'Interessado cadastrado com sucesso.');
    }

    public function edit(Interessado $interessado)
    {
        $origens = OrigemInteressado::where('ativo', true)->orderBy('nome')->get();
        $categorias = CategoriaInteressado::orderBy('nome')->get();
        $cursos = Curso::where('ativo', true)->orderBy('nome')->get();

        return view('crm.interessados.form', compact('interessado', 'origens', 'categorias', 'cursos'));
    }

    public function update(Request $request, Interessado $interessado)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'celular' => 'nullable|string|max:20',
            'origem_id' => 'nullable|exists:origens_interessado,id',
            'categoria_id' => 'nullable|exists:categorias_interessado,id',
            'curso_id' => 'nullable|exists:cursos,id',
            'observacoes' => 'nullable|string',
            'ativo' => 'boolean',
        ]);

        $validated['ativo'] = $request->boolean('ativo');

        $interessado->update($validated);

        return redirect()->route('crm.interessados.index')
            ->with('success', 'Interessado atualizado com sucesso.');
    }

    public function destroy(Interessado $interessado)
    {
        $interessado->delete();

        return redirect()->route('crm.interessados.index')
            ->with('success', 'Interessado removido com sucesso.');
    }
}
