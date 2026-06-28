<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integracao extends Model
{
    protected $table = 'integracoes';

    protected $fillable = ['chave', 'nome', 'ativo', 'credenciais', 'ultima_sincronizacao'];

    protected $casts = [
        'ativo' => 'boolean',
        'credenciais' => 'array',
        'ultima_sincronizacao' => 'datetime',
    ];

    /**
     * Catálogo das integrações suportadas com os campos de credencial de cada uma.
     */
    public static function catalogo(): array
    {
        return [
            'rd_station' => [
                'nome' => 'RD Station Marketing',
                'icone' => 'fa-bullseye',
                'descricao' => 'Sincroniza interessados (leads) e conversões com o RD Station.',
                'campos' => [
                    'token' => 'Token de API (público)',
                    'client_id' => 'Client ID',
                    'client_secret' => 'Client Secret',
                ],
            ],
            'gateway_cartao' => [
                'nome' => 'Gateway de Cartão',
                'icone' => 'fa-credit-card',
                'descricao' => 'Processa pagamentos com cartão (Cielo, Pagar.me, Stripe...).',
                'campos' => [
                    'provedor' => 'Provedor (cielo/pagarme/stripe)',
                    'api_key' => 'API Key',
                    'api_secret' => 'API Secret',
                ],
            ],
            'boleto' => [
                'nome' => 'Boletos Bancários (CNAB)',
                'icone' => 'fa-barcode',
                'descricao' => 'Geração de remessa e leitura de retorno (CNAB 240/400).',
                'campos' => [
                    'banco' => 'Código do Banco (ex.: 341, 001)',
                    'agencia' => 'Agência',
                    'conta' => 'Conta',
                    'carteira' => 'Carteira',
                    'convenio' => 'Convênio/Cedente',
                ],
            ],
            'sms' => [
                'nome' => 'SMS',
                'icone' => 'fa-comment-sms',
                'descricao' => 'Envio de SMS transacionais e campanhas.',
                'campos' => [
                    'provedor' => 'Provedor',
                    'api_key' => 'API Key',
                    'remetente' => 'Remetente',
                ],
            ],
            'whatsapp' => [
                'nome' => 'WhatsApp',
                'icone' => 'fa-whatsapp',
                'descricao' => 'Mensagens via API oficial do WhatsApp Business.',
                'campos' => [
                    'phone_id' => 'Phone Number ID',
                    'token' => 'Access Token',
                ],
            ],
            'nfe' => [
                'nome' => 'Nota Fiscal (NF-e/NFS-e)',
                'icone' => 'fa-file-invoice',
                'descricao' => 'Emissão de notas fiscais de serviço.',
                'campos' => [
                    'provedor' => 'Provedor (ex.: PlugNotas, Focus NFe)',
                    'api_key' => 'API Key',
                    'ambiente' => 'Ambiente (homologacao/producao)',
                ],
            ],
        ];
    }
}
