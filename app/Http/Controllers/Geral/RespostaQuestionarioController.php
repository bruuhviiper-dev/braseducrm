<?php

namespace App\Http\Controllers\Geral;

use App\Http\Controllers\Controller;
use App\Models\Questionario;
use App\Models\RespostaQuestionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RespostaQuestionarioController extends Controller
{
    public function responder(Questionario $questionario)
    {
        $questionario->load('questoes.opcoes');
        return view('geral.questionarios.responder', compact('questionario'));
    }

    public function salvar(Request $request, Questionario $questionario)
    {
        $data = $request->validate([
            'respondente_nome' => 'nullable|string|max:255',
            'respondente_email' => 'nullable|email|max:255',
            'respostas' => 'required|array',
        ]);

        DB::transaction(function () use ($data, $questionario) {
            $resposta = RespostaQuestionario::create([
                'questionario_id' => $questionario->id,
                'respondente_nome' => $data['respondente_nome'] ?? null,
                'respondente_email' => $data['respondente_email'] ?? null,
            ]);

            foreach ($data['respostas'] as $questaoId => $valor) {
                $resposta->respostas()->create([
                    'questao_id' => $questaoId,
                    'valor' => is_numeric($valor) ? $valor : null,
                    'texto' => is_numeric($valor) ? null : $valor,
                ]);
            }
        });

        return redirect()->route('geral.questionarios.index')->with('success', 'Resposta registrada. Obrigado!');
    }

    public function resultados(Questionario $questionario)
    {
        $questionario->load('questoes');

        $totalRespostas = RespostaQuestionario::where('questionario_id', $questionario->id)->count();

        // Cálculo NPS: considera respostas numéricas (escala 0-10).
        $nps = null;
        if ($questionario->tipo === 'nps') {
            $valores = \App\Models\RespostaQuestao::whereHas('questao', fn ($q) => $q->where('tipo', 'escala'))
                ->whereIn('resposta_questionario_id', RespostaQuestionario::where('questionario_id', $questionario->id)->pluck('id'))
                ->whereNotNull('valor')
                ->pluck('valor')
                ->map(fn ($v) => (float) $v);

            $total = $valores->count();
            if ($total > 0) {
                $promotores = $valores->filter(fn ($v) => $v >= 9)->count();
                $detratores = $valores->filter(fn ($v) => $v <= 6)->count();
                $neutros = $total - $promotores - $detratores;
                $nps = [
                    'score' => round((($promotores - $detratores) / $total) * 100, 1),
                    'promotores' => $promotores,
                    'neutros' => $neutros,
                    'detratores' => $detratores,
                    'total' => $total,
                ];
            }
        }

        return view('geral.questionarios.resultados', compact('questionario', 'totalRespostas', 'nps'));
    }
}
