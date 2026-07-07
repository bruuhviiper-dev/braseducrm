<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoPortalInscricao extends Model
{
    protected $table = 'configuracoes_portal_inscricao';

    protected $fillable = ['titulo', 'cor_primaria', 'texto_boas_vindas', 'exigir_cpf', 'permitir_cupom'];

    protected $casts = ['exigir_cpf' => 'boolean', 'permitir_cupom' => 'boolean'];

    public static function current(): self
    {
        return static::firstOrCreate([]);
    }
}
