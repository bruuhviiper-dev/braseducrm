<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassificacaoGed extends Model
{
    protected $table = 'classificacoes_ged';

    protected $fillable = ['nome', 'descricao', 'pai_id', 'ativo'];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function pai()
    {
        return $this->belongsTo(ClassificacaoGed::class, 'pai_id');
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoGed::class, 'classificacao_ged_id');
    }
}
