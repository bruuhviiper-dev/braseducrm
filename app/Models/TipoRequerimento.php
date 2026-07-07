<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoRequerimento extends Model
{
    protected $table = 'tipos_requerimento';

    protected $fillable = [
        'nome', 'cobrado', 'valor', 'descricao', 'ativo',
        'isento', 'vencimento_dias', 'cota_isencao', 'exigir_anexo',
        'bloquear_inadimplente', 'bloquear_parcelas_abertas', 'ocultar_portal',
        'exigir_aprovacao', 'finalizar_apos_pagamento', 'cancelar_sem_pagamento',
        'novo_status_matricula', 'departamento_id', 'categoria_receber_id', 'conta_bancaria_id',
    ];

    protected $casts = [
        'cobrado' => 'boolean',
        'valor' => 'decimal:2',
        'ativo' => 'boolean',
        'isento' => 'boolean',
        'exigir_anexo' => 'boolean',
        'bloquear_inadimplente' => 'boolean',
        'bloquear_parcelas_abertas' => 'boolean',
        'ocultar_portal' => 'boolean',
        'exigir_aprovacao' => 'boolean',
        'finalizar_apos_pagamento' => 'boolean',
        'cancelar_sem_pagamento' => 'boolean',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}
