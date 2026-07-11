<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\HistoricoOportunidade;
use App\Models\Inscricao;
use App\Models\LinkMatriculaOnline;
use Illuminate\Http\Request;

/**
 * Página pública do link de matrícula online (doc CRM: autoatendimento).
 * Com validade configurada, o link é bloqueado após o vencimento (escassez).
 * Ao concluir a inscrição, o card do interessado vai para GANHO automaticamente.
 */
class MatriculaLinkController extends Controller
{
    public function publico(string $token)
    {
        $link = LinkMatriculaOnline::with(['abertura.curso', 'oportunidade.interessado'])
            ->where('token', $token)->firstOrFail();

        return view('crm.matricula-link.publico', ['link' => $link, 'expirado' => $link->expirado()]);
    }

    public function inscrever(Request $request, string $token)
    {
        $link = LinkMatriculaOnline::with(['abertura', 'oportunidade'])->where('token', $token)->firstOrFail();
        abort_if($link->expirado(), 410, 'Este link de matrícula expirou.');

        $v = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'nullable|string|max:30',
            'cpf' => 'nullable|string|max:14',
        ]);

        Inscricao::create($v + [
            'abertura_matricula_id' => $link->abertura_matricula_id,
            'situacao' => 'pendente',
            'pagamento_confirmado' => true, // checkout de demonstração: pagamento aprovado na hora
        ]);

        $op = $link->oportunidade;
        if ($op && in_array($op->situacao, ['aberta', 'pausada'])) {
            $op->update(['situacao' => 'ganha', 'data_fechamento' => now()]);
            HistoricoOportunidade::create([
                'oportunidade_id' => $op->id,
                'user_id' => null,
                'tipo' => 'movimentacao',
                'texto' => 'Matrícula online concluída pelo próprio interessado via link. Card movido para GANHO automaticamente.',
            ]);
        }

        return view('crm.matricula-link.concluido', ['link' => $link, 'nome' => $v['nome']]);
    }
}
