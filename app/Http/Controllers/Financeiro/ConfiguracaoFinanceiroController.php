<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoFinanceiro;
use App\Models\MensagemEnviada;
use App\Models\ReguaCobranca;
use App\Models\ReguaEnvio;
use App\Models\TituloReceber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfiguracaoFinanceiroController extends Controller
{
    public function index()
    {
        $config = ConfiguracaoFinanceiro::current();
        $reguas = ReguaCobranca::orderBy('tipo')->orderBy('dias')->get();
        return view('financeiro.configuracao.index', compact('config', 'reguas'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'multa_atraso' => 'required|numeric|min:0',
            'juros_dia' => 'required|numeric|min:0',
            // Régua de cobrança dos docs do EDUQ: avisos antes, depois do vencimento e na baixa
            'reguas' => 'nullable|array',
            'reguas.*.tipo' => 'nullable|in:antecedencia,atraso,pagamento',
            'reguas.*.dias' => 'nullable|integer|min:0|max:365',
            'reguas.*.canal' => 'nullable|in:email,sms,whatsapp',
            'reguas.*.mensagem' => 'nullable|string|max:1000',
            'reguas.*.filtrar_ja_notificados' => 'nullable|boolean',
        ]);
        $data['boleto_automatico'] = $request->boolean('boleto_automatico');
        $data['cartao_recorrente'] = $request->boolean('cartao_recorrente');

        ConfiguracaoFinanceiro::current()->update(collect($data)->except('reguas')->all());

        DB::transaction(function () use ($data) {
            $manter = [];
            foreach ($data['reguas'] ?? [] as $r) {
                if (empty($r['tipo']) || empty($r['mensagem'])) {
                    continue;
                }
                $regua = ReguaCobranca::updateOrCreate(
                    ['tipo' => $r['tipo'], 'dias' => (int) ($r['dias'] ?? 0), 'canal' => $r['canal'] ?? 'email'],
                    ['mensagem' => $r['mensagem'], 'filtrar_ja_notificados' => !empty($r['filtrar_ja_notificados']), 'ativo' => true]
                );
                $manter[] = $regua->id;
            }
            ReguaCobranca::whereNotIn('id', $manter)->delete();
        });

        return redirect()->route('financeiro.configuracao.index')->with('success', 'Configuração do Financeiro salva.');
    }

    /**
     * Régua de cobrança (docs do EDUQ): dispara os avisos de vencimento (X dias ANTES),
     * as cobranças por atraso (X dias APÓS o vencimento) e as confirmações de pagamento.
     * "Filtrar já notificados" evita mensagem repetida para o mesmo título na mesma régua.
     */
    public function processarReguas()
    {
        $hoje = now()->startOfDay();
        $enviadas = 0;
        $puladas = 0;

        foreach (ReguaCobranca::where('ativo', true)->get() as $regua) {
            $query = TituloReceber::with('pessoa');
            if ($regua->tipo === 'antecedencia') {
                $query->whereIn('situacao', ['aberto', 'vencido'])
                    ->whereDate('data_vencimento', $hoje->copy()->addDays($regua->dias));
            } elseif ($regua->tipo === 'atraso') {
                $query->whereIn('situacao', ['aberto', 'vencido'])
                    ->whereDate('data_vencimento', $hoje->copy()->subDays($regua->dias));
            } else { // pagamento: confirmação da baixa feita hoje
                $query->where('situacao', 'pago')->whereDate('data_pagamento', $hoje);
            }

            foreach ($query->get() as $titulo) {
                $pessoa = $titulo->pessoa;
                if (!$pessoa || $pessoa->nao_receber_mensagens) {
                    $puladas++;
                    continue;
                }
                if ($regua->filtrar_ja_notificados
                    && ReguaEnvio::where('regua_cobranca_id', $regua->id)->where('titulo_receber_id', $titulo->id)->exists()) {
                    $puladas++;
                    continue;
                }

                $destinatario = $regua->canal === 'email'
                    ? ($pessoa->email ?: $pessoa->email_secundario)
                    : ($pessoa->celular ?: $pessoa->telefone);
                if (!$destinatario) {
                    $puladas++;
                    continue;
                }

                MensagemEnviada::create([
                    'pessoa_id' => $pessoa->id,
                    'canal' => $regua->canal,
                    'destinatario' => $destinatario,
                    'assunto' => ReguaCobranca::TIPOS[$regua->tipo] ?? 'Régua de cobrança',
                    'conteudo' => $this->montarMensagem($regua->mensagem, $titulo),
                    'situacao' => 'enviada',
                    'enviado_por' => auth()->id(),
                ]);
                ReguaEnvio::create(['regua_cobranca_id' => $regua->id, 'titulo_receber_id' => $titulo->id]);
                $enviadas++;
            }
        }

        return back()->with('success', "Régua de cobrança processada: {$enviadas} mensagem(ns) enviada(s)"
            . ($puladas ? ", {$puladas} título(s) ignorado(s) (já notificado, sem contato ou opt-out)." : '.'));
    }

    /** Variáveis da mensagem: {nome}, {valor}, {vencimento}, {documento}. */
    private function montarMensagem(string $template, TituloReceber $titulo): string
    {
        return strtr($template, [
            '{nome}' => $titulo->pessoa?->nome ?? '',
            '{valor}' => 'R$ ' . number_format((float) $titulo->valor_original, 2, ',', '.'),
            '{vencimento}' => $titulo->data_vencimento?->format('d/m/Y') ?? '',
            '{documento}' => $titulo->numero_documento ?? ('#' . $titulo->id),
        ]);
    }
}
