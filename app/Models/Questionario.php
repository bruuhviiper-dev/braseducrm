<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionario extends Model
{
    protected $table = 'questionarios';

    protected $fillable = ['nome', 'descricao', 'tipo', 'ativo'];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function questoes()
    {
        return $this->belongsToMany(Questao::class, 'questionario_questoes')
            ->withPivot('ordem', 'obrigatoria')
            ->orderBy('questionario_questoes.ordem');
    }

    public static function tipos(): array
    {
        return [
            'avaliacao_institucional' => 'Avaliação Institucional',
            'nps' => 'NPS',
            'feedback' => 'Feedback',
            'avulso' => 'Avulso',
        ];
    }
}
