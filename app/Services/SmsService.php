<?php

namespace App\Services;

use App\Models\Integracao;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Envio de SMS via provedor configurado (integração "sms").
 */
class SmsService
{
    private ?Integracao $integracao;

    public function __construct()
    {
        $this->integracao = Integracao::where('chave', 'sms')->first();
    }

    public function ativo(): bool
    {
        return $this->integracao
            && $this->integracao->ativo
            && !empty($this->integracao->credenciais['api_key']);
    }

    public function enviar(string $telefone, string $mensagem): array
    {
        if (!$this->ativo()) {
            return ['ok' => false, 'mensagem' => 'SMS não configurado.'];
        }

        $cred = $this->integracao->credenciais;

        try {
            // Endpoint genérico — ajustável ao provedor real do cliente.
            $response = Http::withToken($cred['api_key'])->asJson()->post('https://api.smsprovider.com/v1/send', [
                'from' => $cred['remetente'] ?? null,
                'to' => preg_replace('/\D/', '', $telefone),
                'message' => $mensagem,
            ]);

            if ($response->successful()) {
                $this->integracao->update(['ultima_sincronizacao' => now()]);
                return ['ok' => true, 'mensagem' => 'SMS enviado.'];
            }
            return ['ok' => false, 'mensagem' => 'Falha: HTTP ' . $response->status()];
        } catch (\Throwable $e) {
            Log::error('SMS: exceção', ['erro' => $e->getMessage()]);
            return ['ok' => false, 'mensagem' => 'Erro: ' . $e->getMessage()];
        }
    }
}
