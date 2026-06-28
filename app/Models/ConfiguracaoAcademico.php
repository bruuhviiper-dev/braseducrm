<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoAcademico extends Model
{
    protected $table = 'configuracoes_academico';

    protected $fillable = [
        'assinatura_eletronica', 'envio_email_matricula',
        'aniversariante_automatico', 'email_matricula_template', 'configuracoes',
    ];

    protected $casts = [
        'assinatura_eletronica' => 'boolean',
        'envio_email_matricula' => 'boolean',
        'aniversariante_automatico' => 'boolean',
        'configuracoes' => 'json',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([]);
    }
}
