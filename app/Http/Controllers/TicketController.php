<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\TicketMensagem;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::where('user_id', Auth::id())->orderByDesc('updated_at');

        if ($request->has('situacao') && $request->situacao !== '') {
            $query->where('situacao', $request->situacao);
        }

        $tickets = $query->paginate(15);

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'assunto' => 'required|string|max:255',
            'descricao' => 'required|string',
            'prioridade' => 'required|in:baixa,media,alta,urgente',
        ]);

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'assunto' => $request->assunto,
            'descricao' => $request->descricao,
            'prioridade' => $request->prioridade,
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket criado com sucesso!');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load(['mensagens.user', 'user']);

        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        return view('tickets.form', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'assunto' => 'required|string|max:255',
            'descricao' => 'required|string',
            'prioridade' => 'required|in:baixa,media,alta,urgente',
        ]);

        $ticket->update($request->only(['assunto', 'descricao', 'prioridade']));

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket atualizado com sucesso!');
    }

    public function destroy(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket excluido com sucesso!');
    }

    public function responder(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'mensagem' => 'required|string',
        ]);

        TicketMensagem::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'mensagem' => $request->mensagem,
        ]);

        $ticket->touch();

        return back()->with('success', 'Resposta enviada com sucesso!');
    }
}
