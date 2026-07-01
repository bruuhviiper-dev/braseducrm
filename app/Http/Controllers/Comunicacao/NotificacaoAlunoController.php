<?php

namespace App\Http\Controllers\Comunicacao;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\NotificacaoAluno;
use Illuminate\Http\Request;

class NotificacaoAlunoController extends Controller
{
    public function index()
    {
        $notificacoes = NotificacaoAluno::with('aluno.pessoa')->orderByDesc('id')->paginate(20);
        $stats = [
            'total' => NotificacaoAluno::count(),
            'nao_lidas' => NotificacaoAluno::where('lida', false)->count(),
        ];

        return view('comunicacao.notificacoes.index', compact('notificacoes', 'stats'));
    }

    public function create()
    {
        return view('comunicacao.notificacoes.form', ['alunos' => Aluno::with('pessoa')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'mensagem' => 'nullable|string',
            'tipo' => 'required|in:' . implode(',', array_keys(NotificacaoAluno::TIPOS)),
            'para_todos' => 'nullable|boolean',
            'aluno_id' => 'nullable|required_without:para_todos|exists:alunos,id',
        ]);

        $paraTodos = $request->boolean('para_todos');

        if ($paraTodos) {
            $alunos = Aluno::pluck('id');
            foreach ($alunos as $alunoId) {
                NotificacaoAluno::create([
                    'aluno_id' => $alunoId,
                    'titulo' => $data['titulo'],
                    'mensagem' => $data['mensagem'] ?? null,
                    'tipo' => $data['tipo'],
                    'para_todos' => true,
                ]);
            }
            $msg = 'Notificação enviada para ' . $alunos->count() . ' aluno(s).';
        } else {
            NotificacaoAluno::create([
                'aluno_id' => $data['aluno_id'],
                'titulo' => $data['titulo'],
                'mensagem' => $data['mensagem'] ?? null,
                'tipo' => $data['tipo'],
                'para_todos' => false,
            ]);
            $msg = 'Notificação enviada.';
        }

        return redirect()->route('comunicacao.notificacoes.index')->with('success', $msg);
    }

    public function marcarLida(NotificacaoAluno $notificaco)
    {
        $notificaco->update(['lida' => !$notificaco->lida]);

        return back()->with('success', 'Notificação atualizada.');
    }

    public function destroy(NotificacaoAluno $notificaco)
    {
        $notificaco->delete();

        return back()->with('success', 'Notificação removida.');
    }
}
