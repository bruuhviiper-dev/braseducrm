<?php

namespace App\Http\Controllers\Biblioteca;

use App\Http\Controllers\Controller;
use App\Models\Biblioteca;
use App\Models\Obra;
use App\Models\Pessoa;
use App\Models\ReservaExemplar;
use Illuminate\Http\Request;

class ReservaExemplarController extends Controller
{
    public function index()
    {
        $reservas = ReservaExemplar::with('biblioteca', 'obra', 'pessoa')->orderByDesc('id')->paginate(20);

        return view('biblioteca.reservas.index', compact('reservas'));
    }

    public function create()
    {
        return view('biblioteca.reservas.form', $this->dados());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'biblioteca_id' => 'required|exists:bibliotecas,id',
            'obra_id' => 'required|exists:obras,id',
            'pessoa_id' => 'required|exists:pessoas,id',
            'data_reserva' => 'required|date',
        ]);

        ReservaExemplar::create($data + ['situacao' => 'ativa']);

        return redirect()->route('biblioteca.reservas.index')->with('success', 'Reserva registrada.');
    }

    public function situacao(Request $request, ReservaExemplar $reserva)
    {
        $data = $request->validate(['situacao' => 'required|in:' . implode(',', ReservaExemplar::SITUACOES)]);
        $reserva->update($data);

        return back()->with('success', 'Situação da reserva atualizada.');
    }

    public function destroy(ReservaExemplar $reserva)
    {
        $reserva->delete();

        return redirect()->route('biblioteca.reservas.index')->with('success', 'Reserva removida.');
    }

    private function dados(): array
    {
        return [
            'bibliotecas' => Biblioteca::orderBy('nome')->get(),
            'obras' => Obra::orderBy('titulo')->get(),
            'pessoas' => Pessoa::orderBy('nome')->get(),
        ];
    }
}
