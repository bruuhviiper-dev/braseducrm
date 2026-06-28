<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtoRegulatorio extends Model
{
    protected $table = 'atos_regulatorios';

    protected $fillable = [
        'tipo', 'numero', 'curso_id', 'data_publicacao',
        'validade', 'orgao', 'observacoes', 'ativo',
    ];

    protected $casts = [
        'data_publicacao' => 'date',
        'validade' => 'date',
        'ativo' => 'boolean',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public static function tipos(): array
    {
        return [
            'credenciamento' => 'Credenciamento',
            'recredenciamento' => 'Recredenciamento',
            'autorizacao' => 'Autorização',
            'reconhecimento' => 'Reconhecimento',
            'renovacao' => 'Renovação',
            'outro' => 'Outro',
        ];
    }
}
