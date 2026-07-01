<?php

namespace App\Http\Controllers\Ead;

use App\Http\Controllers\Controller;
use App\Models\CursoEad;
use App\Models\ForumEad;
use App\Models\Pessoa;
use Illuminate\Http\Request;

class ForumEadController extends Controller
{
    public function index()
    {
        $foruns = ForumEad::with('cursoEad')
            ->withCount([
                'mensagens',
                'mensagens as sem_resposta_tutor_count' => fn ($q) => $q->where('do_tutor', false),
            ])
            ->orderByDesc('id')->paginate(20);

        $stats = [
            'topicos' => ForumEad::count(),
            'mensagens' => \App\Models\ForumMensagem::count(),
            'sem_tutor' => \App\Models\ForumMensagem::where('do_tutor', false)->count(),
            'movimentados' => \App\Models\ForumMensagem::where('created_at', '>=', now()->subDays(30))
                ->distinct('forum_ead_id')->count('forum_ead_id'),
        ];

        return view('ead.foruns.index', compact('foruns', 'stats'));
    }

    public function create()
    {
        return view('ead.foruns.form', ['forum' => null, 'cursos' => CursoEad::orderBy('nome')->get()]);
    }

    public function store(Request $request)
    {
        ForumEad::create($this->validar($request));

        return redirect()->route('ead.foruns.index')->with('success', 'Fórum criado com sucesso.');
    }

    public function show(ForumEad $forum)
    {
        $forum->load(['cursoEad', 'mensagens.pessoa']);
        $pessoas = Pessoa::orderBy('nome')->get();

        return view('ead.foruns.show', compact('forum', 'pessoas'));
    }

    public function edit(ForumEad $forum)
    {
        return view('ead.foruns.form', ['forum' => $forum, 'cursos' => CursoEad::orderBy('nome')->get()]);
    }

    public function update(Request $request, ForumEad $forum)
    {
        $forum->update($this->validar($request));

        return redirect()->route('ead.foruns.index')->with('success', 'Fórum atualizado.');
    }

    public function destroy(ForumEad $forum)
    {
        $forum->delete();

        return redirect()->route('ead.foruns.index')->with('success', 'Fórum removido.');
    }

    public function mensagem(Request $request, ForumEad $forum)
    {
        $data = $request->validate([
            'mensagem' => 'required|string',
            'pessoa_id' => 'nullable|exists:pessoas,id',
            'do_tutor' => 'nullable|boolean',
        ]);

        $forum->mensagens()->create([
            'mensagem' => $data['mensagem'],
            'pessoa_id' => $data['pessoa_id'] ?? null,
            'do_tutor' => (bool) ($data['do_tutor'] ?? false),
        ]);

        return redirect()->route('ead.foruns.show', $forum)->with('success', 'Mensagem publicada.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'titulo' => 'required|string|max:255',
            'curso_ead_id' => 'nullable|exists:cursos_ead,id',
        ]);
    }
}
