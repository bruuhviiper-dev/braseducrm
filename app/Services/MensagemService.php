<?php

namespace App\Services;

use App\Models\MensagemEnviada;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * Dispatcher único de mensagens: roteia por canal (email/sms/whatsapp),
 * usa os services de integração e registra em mensagens_enviadas.
 */
class MensagemService
{
    public function enviar(string $canal, string $destinatario, string $conteudo, ?string $assunto = null, ?int $pessoaId = null, ?int $templateId = null): MensagemEnviada
    {
        $situacao = 'enviada';
        $erro = null;

        try {
            $resultado = match ($canal) {
                'sms' => (new SmsService())->enviar($destinatario, $conteudo),
                'whatsapp' => (new WhatsappService())->enviar($destinatario, $conteudo),
                'email' => $this->enviarEmail($destinatario, $assunto ?? 'Mensagem', $conteudo),
                default => ['ok' => false, 'mensagem' => 'Canal inválido.'],
            };

            if (!($resultado['ok'] ?? false)) {
                $situacao = 'erro';
                $erro = $resultado['mensagem'] ?? 'Falha no envio.';
            }
        } catch (\Throwable $e) {
            $situacao = 'erro';
            $erro = $e->getMessage();
            Log::error('MensagemService: exceção', ['erro' => $e->getMessage()]);
        }

        return MensagemEnviada::create([
            'pessoa_id' => $pessoaId,
            'template_id' => $templateId,
            'canal' => $canal,
            'destinatario' => $destinatario,
            'assunto' => $assunto,
            'conteudo' => $conteudo,
            'situacao' => $situacao,
            'erro' => $erro,
            'enviado_por' => auth()->id(),
        ]);
    }

    private function enviarEmail(string $para, string $assunto, string $conteudo): array
    {
        try {
            Mail::raw($conteudo, function ($m) use ($para, $assunto) {
                $m->to($para)->subject($assunto);
            });
            return ['ok' => true, 'mensagem' => 'E-mail enviado.'];
        } catch (\Throwable $e) {
            return ['ok' => false, 'mensagem' => 'Erro de e-mail (SMTP não configurado?): ' . $e->getMessage()];
        }
    }
}
