<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoCrm extends Model
{
    protected $table = 'configuracoes_crm';

    protected $fillable = [
        'roleta_ativa', 'dias_perda_automatica', 'minutos_estagnacao', 'considerar_dias_uteis',
        'rd_station_token', 'rd_station_url', 'configuracoes',
    ];

    protected $casts = [
        'roleta_ativa' => 'boolean',
        'dias_perda_automatica' => 'integer',
        'configuracoes' => 'json',
    ];

    /**
     * Retorna a configuração única do CRM, criando-a se ainda não existir.
     */
    public static function current(): self
    {
        return static::firstOrCreate([]);
    }
}
