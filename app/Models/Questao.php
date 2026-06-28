<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questao extends Model
{
    protected $table = 'questoes';

    protected $fillable = ['enunciado', 'tipo', 'tag_questao_id', 'ativo'];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function opcoes()
    {
        return $this->hasMany(Opcao::class)->orderBy('ordem');
    }

    public function tag()
    {
        return $this->belongsTo(TagQuestao::class, 'tag_questao_id');
    }

    public static function tipos(): array
    {
        return [
            'multipla_escolha' => 'Múltipla Escolha',
            'dissertativa' => 'Dissertativa',
            'escala' => 'Escala',
            'verdadeiro_falso' => 'Verdadeiro/Falso',
        ];
    }
}
