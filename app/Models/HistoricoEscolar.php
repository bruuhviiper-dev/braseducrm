<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricoEscolar extends Model
{
    protected $table = 'historico_escolar';

    protected $fillable = ['matricula_id', 'disciplina_id', 'modulo_id', 'media', 'status', 'observacao'];

    protected $casts = ['media' => 'decimal:2'];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
}
