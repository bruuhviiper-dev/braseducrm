<?php

namespace App\Services;

use App\Models\Integracao;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Emissão de notas fiscais de serviço via provedor (PlugNotas, Focus NFe...) — integração "nfe".
 */
class NfeService
{
    private ?Integracao $integracao;

    public function __construct()
    {
        $this->integracao = Integracao::where('chave', 'nfe')->first();
    }

    public function ativo(): bool
    {
        return $this->integracao
            && $this->integracao->ativo
            && !empty($this->integracao->credenciais['api_key']);
    }

    /**
     * Emite uma NFS-e. Retorna ['ok' => bool, 'id' => ?, 'mensagem' => string].
     */
    public function emitir(array $dados): array
    {
        if (!$this->ativo()) {
            return ['ok' => false, 'mensagem' => 'Emissor de NF-e não configurado.'];
        }

        $cred = $this->integracao->credenciais;
        $ambiente = $cred['ambiente'] ?? 'homologacao';

        try {
            $base = $ambiente === 'producao'
                ? 'https://api.plugnotas.com.br/nfse'
                : 'https://api.sandbox.plugnotas.com.br/nfse';

            $response = Http::withHeaders(['X-API-KEY' => $cred['api_key']])
                ->asJson()->post($base, $dados);

            if ($response->successful()) {
                $this->integracao->update(['ultima_sincronizacao' => now()]);
                return ['ok' => true, 'id' => $response->json('id'), 'mensagem' => 'NF-e enviada para processamento.'];
            }
            return ['ok' => false, 'mensagem' => 'Falha: HTTP ' . $response->status()];
        } catch (\Throwable $e) {
            Log::error('NF-e: exceção', ['erro' => $e->getMessage()]);
            return ['ok' => false, 'mensagem' => 'Erro: ' . $e->getMessage()];
        }
    }
}
