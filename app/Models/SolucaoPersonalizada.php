<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** SOLPER (167 aba Soluções Personalizadas): chave/valor lidos pelo motor de regras customizadas. */
class SolucaoPersonalizada extends Model
{
    protected $table = 'solucoes_personalizadas';

    protected $fillable = ['chave', 'valor'];

    public static function valor(string $chave, ?string $padrao = null): ?string
    {
        return static::where('chave', $chave)->value('valor') ?? $padrao;
    }
}
