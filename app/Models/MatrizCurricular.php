<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatrizCurricular extends Model
{
    protected $table = 'matrizes_curriculares';

    protected $fillable = [
        'nome', 'sigla', 'curso_id', 'area_conhecimento_id', 'grau_id', 'habilitacao_id',
        'configuracao_boletim_id', 'tabela_avaliacao_id', 'estrutura_plano_aula_id', 'estrutura_plano_ensino_id',
        'carga_horaria_total', 'carga_horaria_descritiva', 'situacao', 'ativo', 'inicio_vigencia',
        'observacoes', 'anotacoes',
        'matricular_todas', 'permite_duplicadas', 'percentual_frequencia', 'sistema_curricular',
        'controla_horas_compl', 'horas_compl', 'horas_compl_min',
        'controla_extensao', 'controla_estagio', 'historico_parcial_portal',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'inicio_vigencia' => 'date',
        'matricular_todas' => 'boolean',
        'permite_duplicadas' => 'boolean',
        'controla_horas_compl' => 'boolean',
        'controla_extensao' => 'boolean',
        'controla_estagio' => 'boolean',
        'historico_parcial_portal' => 'boolean',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function areaConhecimento()
    {
        return $this->belongsTo(AreaConhecimento::class);
    }

    public function grau()
    {
        return $this->belongsTo(Grau::class);
    }

    public function habilitacao()
    {
        return $this->belongsTo(Habilitacao::class);
    }

    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'matriz_disciplinas')
            ->withPivot('modulo_id', 'carga_horaria', 'creditos', 'ordem', 'obrigatoria')
            ->withTimestamps();
    }
}
