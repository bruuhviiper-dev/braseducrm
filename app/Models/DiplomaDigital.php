<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiplomaDigital extends Model
{
    protected $table = 'diplomas_digitais';

    protected $fillable = [
        'aluno_id', 'curso_id', 'numero_registro', 'situacao',
        'data_emissao', 'data_colacao', 'observacoes',
    ];

    protected $casts = [
        'data_emissao' => 'date',
        'data_colacao' => 'date',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public static function situacoes(): array
    {
        return [
            'pendente' => 'Pendente',
            'emitido' => 'Emitido',
            'assinado' => 'Assinado',
            'registrado' => 'Registrado',
        ];
    }
}
