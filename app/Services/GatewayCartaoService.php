<?php

namespace App\Services;

use App\Models\Integracao;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Wrapper genérico para gateways de cartão (Pagar.me, Cielo, Stripe...).
 * Funciona quando a integração "gateway_cartao" estiver configurada e ativa.
 */
class GatewayCartaoService
{
    private ?Integracao $integracao;

    public function __construct()
    {
        $this->integracao = Integracao::where('chave', 'gateway_cartao')->first();
    }

    public function ativo(): bool
    {
        return $this->integracao
            && $this->integracao->ativo
            && !empty($this->integracao->credenciais['api_key']);
    }

    /**
     * Cria uma cobrança no gateway. Retorna ['ok' => bool, 'id' => ?, 'mensagem' => string].
     */
    public function cobrar(float $valor, array $dadosCartao, string $descricao = ''): array
    {
        if (!$this->ativo()) {
            return ['ok' => false, 'mensagem' => 'Gateway de cartão não configurado.'];
        }

        $cred = $this->integracao->credenciais;
        $provedor = $cred['provedor'] ?? 'pagarme';

        try {
            $endpoints = [
                'pagarme' => 'https://api.pagar.me/core/v5/orders',
                'stripe' => 'https://api.stripe.com/v1/charges',
                'cielo' => 'https://api.cieloecommerce.cielo.com.br/1/sales',
            ];
            $url = $endpoints[$provedor] ?? $endpoints['pagarme'];

            $response = Http::withToken($cred['api_key'])->asJson()->post($url, [
                'amount' => (int) round($valor * 100),
                'description' => $descricao,
                'card' => $dadosCartao,
            ]);

            if ($response->successful()) {
                $this->integracao->update(['ultima_sincronizacao' => now()]);
                return ['ok' => true, 'id' => $response->json('id'), 'mensagem' => 'Cobrança criada.'];
            }
            Log::warning('Gateway cartão: falha', ['status' => $response->status()]);
            return ['ok' => false, 'mensagem' => 'Falha no gateway: HTTP ' . $response->status()];
        } catch (\Throwable $e) {
            Log::error('Gateway cartão: exceção', ['erro' => $e->getMessage()]);
            return ['ok' => false, 'mensagem' => 'Erro: ' . $e->getMessage()];
        }
    }
}
