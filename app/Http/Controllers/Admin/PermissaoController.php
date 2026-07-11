<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\PermissaoDepartamento;
use App\Models\PermissaoUsuarioExtra;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Catálogo de Permissões (doc "Cadastro de Grupo de Operadores"):
 * checkboxes por função (Adicionar/Editar/Remover/Detalhar/especiais) +
 * "OCULTAR NO MENU", salvos por departamento; o usuário pode receber
 * liberações extras além do departamento. Administrador tem acesso total.
 */
class PermissaoController extends Controller
{
    public function departamento(Departamento $departamento)
    {
        $marcadas = PermissaoDepartamento::where('departamento_id', $departamento->id)->get()
            ->groupBy('funcao_codigo')->map(fn ($g) => $g->pluck('acao')->all());

        return view('admin.permissoes.editar', [
            'catalogo' => config('catalogo_permissoes'),
            'marcadas' => $marcadas,
            'titulo' => 'Permissões do Departamento: ' . $departamento->nome,
            'subtitulo' => 'Funções que este departamento pode usar. Usuários do departamento herdam estas permissões.',
            'action' => route('admin.departamentos.permissoes.salvar', $departamento),
            'voltar' => route('admin.departamentos.index'),
            'comOcultar' => true,
        ]);
    }

    public function salvarDepartamento(Request $request, Departamento $departamento)
    {
        DB::transaction(function () use ($request, $departamento) {
            PermissaoDepartamento::where('departamento_id', $departamento->id)->delete();
            foreach ($this->linhas($request) as [$codigo, $acao]) {
                PermissaoDepartamento::create(['departamento_id' => $departamento->id, 'funcao_codigo' => $codigo, 'acao' => $acao]);
            }
        });

        return back()->with('success', 'Permissões do departamento salvas.');
    }

    public function usuario(User $operador)
    {
        $marcadas = PermissaoUsuarioExtra::where('user_id', $operador->id)->get()
            ->groupBy('funcao_codigo')->map(fn ($g) => $g->pluck('acao')->all());
        $doDepartamento = $operador->departamento_id
            ? PermissaoDepartamento::where('departamento_id', $operador->departamento_id)->get()
                ->groupBy('funcao_codigo')->map(fn ($g) => $g->pluck('acao')->all())
            : collect();

        return view('admin.permissoes.editar', [
            'catalogo' => config('catalogo_permissoes'),
            'marcadas' => $marcadas,
            'herdadas' => $doDepartamento,
            'titulo' => 'Liberações extras: ' . $operador->nome,
            'subtitulo' => 'Permissões ADICIONAIS além das do departamento ' . ($operador->departamento->nome ?? '(sem departamento)') . '. As herdadas aparecem marcadas em cinza.',
            'action' => route('admin.operadores.permissoes.salvar', $operador),
            'voltar' => route('admin.operadores.index'),
            'comOcultar' => false,
        ]);
    }

    public function salvarUsuario(Request $request, User $operador)
    {
        DB::transaction(function () use ($request, $operador) {
            PermissaoUsuarioExtra::where('user_id', $operador->id)->delete();
            foreach ($this->linhas($request) as [$codigo, $acao]) {
                PermissaoUsuarioExtra::create(['user_id' => $operador->id, 'funcao_codigo' => $codigo, 'acao' => $acao]);
            }
        });

        return back()->with('success', 'Liberações extras do operador salvas.');
    }

    /** Entrada: perms[] = "codigo|acao". */
    private function linhas(Request $request): array
    {
        $out = [];
        foreach ((array) $request->input('perms', []) as $p) {
            [$codigo, $acao] = array_pad(explode('|', $p, 2), 2, null);
            if (is_numeric($codigo) && $acao !== null && mb_strlen($acao) <= 120) {
                $out[] = [(int) $codigo, $acao];
            }
        }

        return array_values(array_unique($out, SORT_REGULAR));
    }
}
