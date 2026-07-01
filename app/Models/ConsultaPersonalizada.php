<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultaPersonalizada extends Model
{
    protected $table = 'consultas_personalizadas';
    protected $fillable = ['nome', 'entidade', 'campos', 'filtro_campo', 'filtro_operador', 'filtro_valor', 'ativo'];
    protected $casts = ['campos' => 'array', 'ativo' => 'boolean'];

    /** Registro de entidades consultáveis com whitelist de campos (segurança). */
    public static function entidades(): array
    {
        return [
            'pessoas' => [
                'model' => \App\Models\Pessoa::class,
                'label' => 'Pessoas',
                'campos' => ['nome' => 'Nome', 'email' => 'E-mail', 'telefone' => 'Telefone', 'celular' => 'Celular', 'cpf' => 'CPF', 'cidade' => 'Cidade'],
            ],
            'interessados' => [
                'model' => \App\Models\Interessado::class,
                'label' => 'Interessados (CRM)',
                'campos' => ['nome' => 'Nome', 'email' => 'E-mail', 'telefone' => 'Telefone', 'celular' => 'Celular'],
            ],
            'oportunidades' => [
                'model' => \App\Models\Oportunidade::class,
                'label' => 'Oportunidades (CRM)',
                'campos' => ['titulo' => 'Título', 'valor' => 'Valor', 'situacao' => 'Situação'],
            ],
            'titulos_receber' => [
                'model' => \App\Models\TituloReceber::class,
                'label' => 'Títulos a Receber',
                'campos' => ['numero_documento' => 'Documento', 'valor_original' => 'Valor', 'situacao' => 'Situação', 'data_vencimento' => 'Vencimento'],
            ],
            'produtos_estoque' => [
                'model' => \App\Models\ProdutoEstoque::class,
                'label' => 'Produtos de Estoque',
                'campos' => ['nome' => 'Produto', 'codigo' => 'Código', 'estoque_atual' => 'Estoque', 'preco_custo' => 'Custo'],
            ],
        ];
    }

    public const OPERADORES = ['contem' => 'Contém', 'igual' => 'Igual a', 'maior' => 'Maior que', 'menor' => 'Menor que'];
}
