<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoNfse extends Model
{
    protected $table = 'configuracoes_nfse';

    protected $fillable = [
        'ambiente', 'regime_tributario', 'inscricao_municipal', 'serie_rps',
        'numero_rps_atual', 'codigo_servico', 'aliquota_iss', 'iss_retido',
        'discriminacao_padrao', 'ativo',
    ];

    protected $casts = [
        'aliquota_iss' => 'decimal:2',
        'iss_retido' => 'boolean',
        'ativo' => 'boolean',
    ];

    public const AMBIENTES = ['homologacao' => 'Homologação', 'producao' => 'Produção'];

    public const REGIMES = [
        'simples_nacional' => 'Simples Nacional',
        'lucro_presumido' => 'Lucro Presumido',
        'lucro_real' => 'Lucro Real',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([]);
    }
}
