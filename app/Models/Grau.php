<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grau extends Model
{
    protected $fillable = ['nome', 'codigo_cnae', 'aliquota_iss', 'codigo_servico_lc116', 'codigo_servico_municipal', 'codigo_nbs', 'codigo_tributacao_nacional', 'ibs_cbs_classificacao', 'ibs_cbs_indicador', 'nfe_percentual_personalizado'];
}
