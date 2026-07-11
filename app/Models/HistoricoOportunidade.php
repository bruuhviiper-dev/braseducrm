<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Linha do tempo do card (doc CRM): anotações, anexos, atendimentos, atividades, disparos e movimentações. */
class HistoricoOportunidade extends Model
{
    protected $table = 'historicos_oportunidade';

    public const TIPOS = ['anotacao' => 'Anotação', 'anexo' => 'Anexos', 'atendimento' => 'Atendimento', 'atividade' => 'Atividades', 'disparo' => 'Disparo', 'movimentacao' => 'Movimentação'];

    protected $fillable = ['oportunidade_id', 'user_id', 'tipo', 'texto', 'arquivo'];

    public function oportunidade()
    {
        return $this->belongsTo(Oportunidade::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function registrar(int $oportunidadeId, string $tipo, ?string $texto, ?string $arquivo = null): self
    {
        return self::create([
            'oportunidade_id' => $oportunidadeId,
            'user_id' => auth()->id(),
            'tipo' => $tipo,
            'texto' => $texto,
            'arquivo' => $arquivo,
        ]);
    }
}
