<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoPortal extends Model
{
    protected $table = 'configuracoes_portal';

    protected $fillable = [
        'nome_portal', 'cor_primaria', 'mensagem_boas_vindas',
        'exibe_financeiro', 'exibe_boletim', 'exibe_documentos', 'ativo',
    ];

    protected $casts = [
        'exibe_financeiro' => 'boolean',
        'exibe_boletim' => 'boolean',
        'exibe_documentos' => 'boolean',
        'ativo' => 'boolean',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([]);
    }
}
