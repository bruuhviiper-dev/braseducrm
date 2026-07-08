<?php

namespace App\Services;

use App\Models\Integracao;
use App\Models\Interessado;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RdStationService
{
    private ?Integracao $integracao;

    public function __construct()
    {
        $this->integracao = Integracao::where('chave', 'rd_station')->first();
    }

    public function ativo(): bool
    {
        return $this->integracao
            && $this->integracao->ativo
            && !empty($this->integracao->credenciais['token']);
    }

    /**
     * Envia um interessado como conversão para o RD Station.
     * Retorna true em sucesso; loga e retorna false em falha.
     */
    public function enviarLead(Interessado $interessado): bool
    {
        if (!$this->ativo()) {
            return false;
        }

        $token = $this->integracao->credenciais['token'];

        try {
            $response = Http::asJson()->post(
                'https://api.rd.services/platform/conversions?api_key=' . urlencode($token),
                [
                    'event_type' => 'CONVERSION',
                    'event_family' => 'CDP',
                    'payload' => [
                        'conversion_identifier' => 'one-interessado',
                        'name' => $interessado->nome,
                        'email' => $interessado->email,
                        'mobile_phone' => $interessado->celular ?? $interessado->telefone,
                    ],
                ]
            );

            if ($response->successful()) {
                $this->integracao->update(['ultima_sincronizacao' => now()]);
                return true;
            }

            Log::warning('RD Station: falha ao enviar lead', ['status' => $response->status(), 'body' => $response->body()]);
            return false;
        } catch (\Throwable $e) {
            Log::error('RD Station: exceção ao enviar lead', ['erro' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Testa a conexão com a API do RD Station.
     */
    public function testarConexao(): array
    {
        if (empty($this->integracao?->credenciais['token'])) {
            return ['ok' => false, 'mensagem' => 'Token não configurado.'];
        }

        try {
            $token = $this->integracao->credenciais['token'];
            $response = Http::timeout(10)->get('https://api.rd.services/platform/contacts/email:teste@example.com', [
                'api_key' => $token,
            ]);
            // 404 (contato não existe) ainda indica token válido; 401 indica token inválido.
            if ($response->status() === 401) {
                return ['ok' => false, 'mensagem' => 'Token inválido (401).'];
            }
            return ['ok' => true, 'mensagem' => 'Conexão estabelecida com o RD Station.'];
        } catch (\Throwable $e) {
            return ['ok' => false, 'mensagem' => 'Erro de conexão: ' . $e->getMessage()];
        }
    }
}
