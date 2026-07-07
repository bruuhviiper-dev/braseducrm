<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimentacaoMatricula extends Model
{
    protected $table = 'movimentacoes_matricula';

    protected $fillable = ['matricula_id', 'user_id', 'descricao', 'situacao', 'tag'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Loga uma movimentação da matrícula (alimenta a aba Histórico de Movimentações da ficha 23). */
    public static function registrar(int $matriculaId, string $descricao, ?string $situacao = null, ?string $tag = null): void
    {
        static::create([
            'matricula_id' => $matriculaId,
            'user_id' => auth()->id(),
            'descricao' => $descricao,
            'situacao' => $situacao,
            'tag' => $tag,
        ]);
    }
}
