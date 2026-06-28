<?php

namespace App\Services;

use App\Models\Integracao;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Envio de mensagens via API oficial do WhatsApp Business (integração "whatsapp").
 */
class WhatsappService
{
    private ?Integracao $integracao;

    public function __construct()
    {
        $this->integracao = Integracao::where('chave', 'whatsapp')->first();
    }

    public function ativo(): bool
    {
        return $this->integracao
            && $this->integracao->ativo
            && !empty($this->integracao->credenciais['token'])
            && !empty($this->integracao->credenciais['phone_id']);
    }

    public function enviar(string $telefone, string $mensagem): array
    {
        if (!$this->ativo()) {
            return ['ok' => false, 'mensagem' => 'WhatsApp não configurado.'];
        }

        $cred = $this->integracao->credenciais;

        try {
            $url = "https://graph.facebook.com/v19.0/{$cred['phone_id']}/messages";
            $response = Http::withToken($cred['token'])->asJson()->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => preg_replace('/\D/', '', $telefone),
                'type' => 'text',
                'text' => ['body' => $mensagem],
            ]);

            if ($response->successful()) {
                $this->integracao->update(['ultima_sincronizacao' => now()]);
                return ['ok' => true, 'mensagem' => 'Mensagem enviada.'];
            }
            return ['ok' => false, 'mensagem' => 'Falha: HTTP ' . $response->status()];
        } catch (\Throwable $e) {
            Log::error('WhatsApp: exceção', ['erro' => $e->getMessage()]);
            return ['ok' => false, 'mensagem' => 'Erro: ' . $e->getMessage()];
        }
    }
}
