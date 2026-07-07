<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Controller genérico para os cadastros-base (tabelas de apoio) que seguem
 * o padrão CRUD simples. Configurado pelo registro em tipos().
 */
class CadastroSimplesController extends Controller
{
    /**
     * Registro de todos os cadastros simples.
     * Cada campo: ['name','label','type'(text|number|textarea|boolean),'required'(bool)]
     */
    public static function tipos(): array
    {
        $nome = [['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true]];

        return [
            // Acadêmico
            'areas' => ['model' => \App\Models\AreaConhecimento::class, 'titulo' => 'Área', 'codigo' => 24, 'fields' => [
                ['name' => 'nome', 'label' => 'Descrição', 'type' => 'text', 'required' => true],
            ]],
            'graus' => ['model' => \App\Models\Grau::class, 'titulo' => 'Grau', 'codigo' => 28, 'fields' => [
                ['name' => 'nome', 'label' => 'Descrição', 'type' => 'text', 'required' => true],
                ['name' => 'codigo_cnae', 'label' => 'Código CNAE', 'type' => 'text', 'required' => false],
                ['name' => 'aliquota_iss', 'label' => 'Alíquota ISS', 'type' => 'number', 'required' => false],
                ['name' => 'codigo_servico_lc116', 'label' => 'Código de serviço (Lista Serviço, vide LC116)', 'type' => 'text', 'required' => false],
                ['name' => 'codigo_servico_municipal', 'label' => 'Código do serviço municipal', 'type' => 'text', 'required' => false],
                ['name' => 'codigo_nbs', 'label' => 'Código NBS', 'type' => 'text', 'required' => false],
                ['name' => 'codigo_tributacao_nacional', 'label' => 'Código de Tributação Nacional', 'type' => 'text', 'required' => false],
                ['name' => 'ibs_cbs_classificacao', 'label' => 'IBS/CBS - Classificação Tributária', 'type' => 'text', 'required' => false],
                ['name' => 'ibs_cbs_indicador', 'label' => 'IBS/CBS - Cód. Indicador de Operação', 'type' => 'text', 'required' => false],
                ['name' => 'nfe_percentual_personalizado', 'label' => 'Utilizar percentual personalizado para geração de NF-e?', 'type' => 'boolean', 'required' => false],
            ]],
            'habilitacoes' => ['model' => \App\Models\Habilitacao::class, 'titulo' => 'Habilitação', 'codigo' => 29, 'fields' => [
                ['name' => 'nome', 'label' => 'Descrição', 'type' => 'text', 'required' => true],
                ['name' => 'titulo_conferido', 'label' => 'Título conferido', 'type' => 'text', 'required' => false],
            ]],
            'modulos' => ['model' => \App\Models\Modulo::class, 'titulo' => 'Módulos', 'codigo' => 31, 'fields' => [
                ['name' => 'nome', 'label' => 'Descrição', 'type' => 'text', 'required' => true],
            ]],
            'conceitos-nota' => ['model' => \App\Models\Conceito::class, 'titulo' => 'Conceito de Notas', 'codigo' => 176, 'fields' => [
                ['name' => 'descricao', 'label' => 'Descrição', 'type' => 'text', 'required' => true],
                ['name' => 'conceito', 'label' => 'Conceito (sigla)', 'type' => 'text', 'required' => true],
                ['name' => 'nota_minima', 'label' => 'Nota Mínima', 'type' => 'number', 'required' => true],
                ['name' => 'nota_maxima', 'label' => 'Nota Máxima', 'type' => 'number', 'required' => true],
            ]],
            'motivos-cancelamento' => ['model' => \App\Models\MotivoCancelamentoMatricula::class, 'titulo' => 'Motivo de Cancelamento Matrícula', 'codigo' => 242, 'fields' => $nome],
            'tags-matricula' => ['model' => \App\Models\TagMatricula::class, 'titulo' => 'Tag de Matrícula', 'codigo' => 169, 'fields' => $nome],
            'tags-turma-montada' => ['model' => \App\Models\TagTurmaMontada::class, 'titulo' => 'Tag (Turma Montada)', 'codigo' => 251, 'fields' => $nome],
            'topicos-plano' => ['model' => \App\Models\TopicoPlano::class, 'titulo' => 'Tópico do Plano', 'codigo' => 203, 'fields' => [
                ['name' => 'nome', 'label' => 'Descrição', 'type' => 'text', 'required' => true],
                ['name' => 'obrigatoria', 'label' => 'É obrigatória?', 'type' => 'boolean', 'required' => false],
            ]],
            // Administrativo
            'religioes' => ['model' => \App\Models\Religiao::class, 'titulo' => 'Religião', 'codigo' => 13, 'fields' => $nome],
            'profissoes' => ['model' => \App\Models\Profissao::class, 'titulo' => 'Profissão', 'codigo' => 145, 'fields' => $nome],
            'tipos-profissional' => ['model' => \App\Models\TipoProfissional::class, 'titulo' => 'Tipo de Profissional', 'codigo' => 14, 'fields' => $nome],
            'titularidades' => ['model' => \App\Models\Titularidade::class, 'titulo' => 'Titularidade', 'codigo' => 15, 'fields' => $nome],
            'alergias' => ['model' => \App\Models\Alergia::class, 'titulo' => 'Alergia', 'codigo' => 198, 'fields' => $nome],
            'necessidades-especiais' => ['model' => \App\Models\NecessidadeEspecial::class, 'titulo' => 'Necessidade Especial', 'codigo' => 10, 'fields' => [
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
                ['name' => 'descricao', 'label' => 'Descrição', 'type' => 'textarea', 'required' => false],
            ]],
            'formas-ingresso' => ['model' => \App\Models\FormaIngresso::class, 'titulo' => 'Forma de Ingresso', 'codigo' => 21, 'fields' => [
                ['name' => 'nome', 'label' => 'Descrição', 'type' => 'text', 'required' => true],
            ]],
            'tipos-requerimento' => ['model' => \App\Models\TipoRequerimento::class, 'titulo' => 'Cadastro de Requerimentos', 'codigo' => 94, 'fields' => [
                ['name' => 'nome', 'label' => 'Descrição', 'type' => 'text', 'required' => true],
                ['name' => 'descricao', 'label' => 'Orientações para o aluno', 'type' => 'textarea', 'required' => false],
                ['name' => 'ativo', 'label' => 'Ativo', 'type' => 'boolean', 'required' => false],
                ['name' => 'ocultar_portal', 'label' => 'Ocultar no Portal do Aluno? (uso interno da secretaria)', 'type' => 'boolean', 'required' => false],
                ['name' => 'exigir_anexo', 'label' => 'Exigir anexo na abertura? (ex.: atestado na Justificativa de Falta)', 'type' => 'boolean', 'required' => false],
                ['name' => 'bloquear_inadimplente', 'label' => 'Bloquear abertura se houver parcela vencida (inadimplência)?', 'type' => 'boolean', 'required' => false],
                ['name' => 'bloquear_parcelas_abertas', 'label' => 'Bloquear se houver parcelas em aberto (mesmo a vencer)?', 'type' => 'boolean', 'required' => false],
                ['name' => 'exigir_aprovacao', 'label' => 'Exigir aprovação (direciona para a fila do departamento)?', 'type' => 'boolean', 'required' => false],
                ['name' => 'departamento_id', 'label' => 'Departamento responsável', 'type' => 'select', 'required' => false, 'options' => \App\Models\Departamento::orderBy('nome')->pluck('nome', 'id')->all()],
                ['name' => 'novo_status_matricula', 'label' => 'Ao aprovar, alterar matrícula para', 'type' => 'select', 'required' => false, 'options' => ['trancada' => 'Trancado', 'desistente' => 'Desistente', 'cancelada' => 'Cancelado']],
                ['name' => 'isento', 'label' => 'Isento de taxa?', 'type' => 'boolean', 'required' => false],
                ['name' => 'valor', 'label' => 'Valor da taxa (R$)', 'type' => 'number', 'required' => false],
                ['name' => 'vencimento_dias', 'label' => 'Vencimento do boleto (dias úteis após a abertura)', 'type' => 'number', 'required' => false],
                ['name' => 'cota_isencao', 'label' => 'Cota de isenção (nº de pedidos gratuitos antes de cobrar)', 'type' => 'number', 'required' => false],
                ['name' => 'categoria_receber_id', 'label' => 'Categoria da receita', 'type' => 'select', 'required' => false, 'options' => \App\Models\CategoriaReceber::orderBy('nome')->pluck('nome', 'id')->all()],
                ['name' => 'conta_bancaria_id', 'label' => 'Conta de recebimento', 'type' => 'select', 'required' => false, 'options' => \App\Models\ContaBancaria::orderBy('nome')->pluck('nome', 'id')->all()],
                ['name' => 'finalizar_apos_pagamento', 'label' => 'Finalizar automaticamente após o pagamento?', 'type' => 'boolean', 'required' => false],
                ['name' => 'cancelar_sem_pagamento', 'label' => 'Cancelar automaticamente por falta de pagamento?', 'type' => 'boolean', 'required' => false],
            ]],
            'categorias-atendimento' => ['model' => \App\Models\CategoriaAtendimento::class, 'titulo' => 'Cadastro de Categorias (Atendimento)', 'codigo' => 54, 'fields' => [
                ['name' => 'nome', 'label' => 'Descrição', 'type' => 'text', 'required' => true],
                ['name' => 'departamento_id', 'label' => 'Departamento responsável (todos os operadores do departamento recebem o alerta)', 'type' => 'select', 'required' => false, 'options' => \App\Models\Departamento::orderBy('nome')->pluck('nome', 'id')->all()],
            ]],
            'motivos-falha-atendimento' => ['model' => \App\Models\MotivoFalhaAtendimento::class, 'titulo' => 'Cadastro Motivos de Falha (Atendimentos)', 'codigo' => 178, 'fields' => [
                ['name' => 'nome', 'label' => 'Descrição', 'type' => 'text', 'required' => true],
            ]],
            'escolas' => ['model' => \App\Models\Escola::class, 'titulo' => 'Escola', 'codigo' => 8, 'fields' => [
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
                ['name' => 'telefone', 'label' => 'Telefone (Fixo)', 'type' => 'text', 'required' => false],
                ['name' => 'cidade', 'label' => 'Cidade', 'type' => 'text', 'required' => true],
                ['name' => 'uf', 'label' => 'UF', 'type' => 'text', 'required' => false],
                ['name' => 'tipo_escola', 'label' => 'Tipo da Escola', 'type' => 'select', 'required' => true, 'options' => \App\Models\Escola::TIPOS],
            ]],
            'instituicoes' => ['model' => \App\Models\InstituicaoEnsino::class, 'titulo' => 'Instituição de Ensino', 'codigo' => 7, 'fields' => [
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
                ['name' => 'cnpj', 'label' => 'CNPJ', 'type' => 'text', 'required' => false],
                ['name' => 'razao_social', 'label' => 'Razão Social', 'type' => 'text', 'required' => false],
                ['name' => 'cidade', 'label' => 'Cidade', 'type' => 'text', 'required' => false],
                ['name' => 'uf', 'label' => 'UF', 'type' => 'text', 'required' => false],
                ['name' => 'telefone', 'label' => 'Telefone', 'type' => 'text', 'required' => false],
                ['name' => 'email', 'label' => 'E-mail', 'type' => 'text', 'required' => false],
            ]],
            // Financeiro
            'bancos' => ['model' => \App\Models\Banco::class, 'titulo' => 'Cadastro de Banco', 'codigo' => 47, 'sem_criar' => true, 'fields' => [
                ['name' => 'codigo', 'label' => 'Código (FEBRABAN)', 'type' => 'text', 'required' => false],
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
            ]],
            'centros-custo' => ['model' => \App\Models\CentroCusto::class, 'titulo' => 'Centro de Custos', 'codigo' => 274, 'fields' => [
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
                ['name' => 'codigo', 'label' => 'Código', 'type' => 'text', 'required' => false],
            ]],
            'formas-pagamento' => ['model' => \App\Models\FormaPagamento::class, 'titulo' => 'Forma de Pagamento', 'codigo' => 53, 'fields' => [
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
                ['name' => 'tipo', 'label' => 'Tipo', 'type' => 'select', 'required' => false, 'options' => \App\Models\FormaPagamento::TIPOS],
            ]],
            'motivos-devolucao-cheque' => ['model' => \App\Models\MotivoDevolucaoCheque::class, 'titulo' => 'Motivo de Devolução (Cheque)', 'codigo' => 73, 'fields' => $nome],
            // Matrícula Online
            'tags-matricula-online' => ['model' => \App\Models\TagMatriculaOnline::class, 'titulo' => 'Tag de Matrícula Online', 'codigo' => 74, 'fields' => [
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
                ['name' => 'cor', 'label' => 'Cor (hex ou nome)', 'type' => 'text', 'required' => false],
            ]],
            // Comunicação
            'numeros-whatsapp' => ['model' => \App\Models\NumeroWhatsapp::class, 'titulo' => 'Número de WhatsApp', 'codigo' => 247, 'fields' => [
                ['name' => 'numero', 'label' => 'Número (com DDD)', 'type' => 'text', 'required' => true],
                ['name' => 'descricao', 'label' => 'Descrição', 'type' => 'text', 'required' => false],
                ['name' => 'principal', 'label' => 'Número principal?', 'type' => 'boolean', 'required' => false],
            ]],
            // Geral
            'atributos-adicionais' => ['model' => \App\Models\AtributoAdicional::class, 'titulo' => 'Atributo Adicional', 'codigo' => 97, 'fields' => [
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
                ['name' => 'entidade', 'label' => 'Aplicar em', 'type' => 'select', 'required' => true, 'options' => \App\Models\AtributoAdicional::ENTIDADES],
                ['name' => 'tipo', 'label' => 'Tipo', 'type' => 'select', 'required' => true, 'options' => \App\Models\AtributoAdicional::TIPOS],
                ['name' => 'obrigatorio', 'label' => 'Obrigatório?', 'type' => 'boolean', 'required' => false],
            ]],
            'assinaturas' => ['model' => \App\Models\Assinatura::class, 'titulo' => 'Assinatura', 'codigo' => 6, 'fields' => [
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
                ['name' => 'cargo', 'label' => 'Cargo', 'type' => 'text', 'required' => false],
            ]],
            'cabecalhos' => ['model' => \App\Models\Cabecalho::class, 'titulo' => 'Modelo de Cabeçalho', 'codigo' => 48, 'fields' => [
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
                ['name' => 'conteudo', 'label' => 'Conteúdo (HTML)', 'type' => 'textarea', 'required' => false],
            ]],
            // CRM
            'acoes-automaticas' => ['model' => \App\Models\AcaoAutomaticaCrm::class, 'titulo' => 'Ação Automática (CRM)', 'codigo' => 256, 'fields' => [
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
                ['name' => 'gatilho', 'label' => 'Quando (gatilho)', 'type' => 'select', 'required' => true, 'options' => \App\Models\AcaoAutomaticaCrm::GATILHOS],
                ['name' => 'acao', 'label' => 'Fazer (ação)', 'type' => 'select', 'required' => true, 'options' => \App\Models\AcaoAutomaticaCrm::ACOES],
                ['name' => 'detalhes', 'label' => 'Detalhes', 'type' => 'textarea', 'required' => false],
            ]],
            'motivos-perda' => ['model' => \App\Models\MotivoPerda::class, 'titulo' => 'Motivo de Perda', 'codigo' => 107, 'fields' => $nome],
            'motivos-ganho' => ['model' => \App\Models\MotivoGanho::class, 'titulo' => 'Motivo de Ganho', 'codigo' => 212, 'fields' => $nome],
            'motivos-pausa' => ['model' => \App\Models\MotivoPausa::class, 'titulo' => 'Motivo de Pausa', 'codigo' => 202, 'fields' => $nome],
            'categorias-interessado' => ['model' => \App\Models\CategoriaInteressado::class, 'titulo' => 'Categoria de Interessado', 'codigo' => 207, 'fields' => $nome],
            'produtos-servico' => ['model' => \App\Models\ProdutoServicoCrm::class, 'titulo' => 'Produto/Serviço CRM', 'codigo' => 206, 'fields' => [
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
                ['name' => 'valor', 'label' => 'Valor (R$)', 'type' => 'number', 'required' => false],
                ['name' => 'descricao', 'label' => 'Descrição', 'type' => 'textarea', 'required' => false],
            ]],
            // Estoque
            'depositos' => ['model' => \App\Models\Deposito::class, 'titulo' => 'Depósito de Estoque', 'codigo' => 153, 'fields' => [
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
                ['name' => 'localizacao', 'label' => 'Localização', 'type' => 'text', 'required' => false],
            ]],
            // Biblioteca
            'bibliotecas' => ['model' => \App\Models\Biblioteca::class, 'titulo' => 'Biblioteca', 'codigo' => 291, 'fields' => $nome],
            'colecoes' => ['model' => \App\Models\Colecao::class, 'titulo' => 'Coleção', 'codigo' => 292, 'fields' => $nome],
            'editores' => ['model' => \App\Models\Editor::class, 'titulo' => 'Editor', 'codigo' => 293, 'fields' => $nome],
            'estados-conservacao' => ['model' => \App\Models\EstadoConservacao::class, 'titulo' => 'Estado de Conservação', 'codigo' => 294, 'fields' => $nome],
            'idiomas' => ['model' => \App\Models\Idioma::class, 'titulo' => 'Idioma', 'codigo' => 295, 'fields' => $nome],
            'tipos-aquisicao' => ['model' => \App\Models\TipoAquisicao::class, 'titulo' => 'Tipo de Aquisição', 'codigo' => 297, 'fields' => $nome],
            'tipos-material' => ['model' => \App\Models\TipoMaterial::class, 'titulo' => 'Tipo de Material', 'codigo' => 298, 'fields' => $nome],
            'motivos-indisponibilidade' => ['model' => \App\Models\MotivoIndisponibilidade::class, 'titulo' => 'Motivo de Indisponibilidade', 'codigo' => 296, 'fields' => $nome],
            'autores' => ['model' => \App\Models\Autor::class, 'titulo' => 'Autor', 'codigo' => 290, 'fields' => [
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
                ['name' => 'sobrenome', 'label' => 'Sobrenome', 'type' => 'text', 'required' => false],
            ]],
            // EAD
            'agrupadores-curso' => ['model' => \App\Models\AgrupadorCurso::class, 'titulo' => 'Agrupador de Cursos', 'codigo' => 211, 'fields' => $nome],
            'tags-curso-ead' => ['model' => \App\Models\TagCursoEad::class, 'titulo' => 'Tag (Curso EAD)', 'codigo' => 246, 'fields' => $nome],
            'tags-questao' => ['model' => \App\Models\TagQuestao::class, 'titulo' => 'Tag de Questões', 'codigo' => 236, 'fields' => $nome],
        ];
    }

    private function config(string $tipo): array
    {
        $tipos = static::tipos();
        abort_unless(isset($tipos[$tipo]), 404);
        return $tipos[$tipo];
    }

    public function index(string $tipo)
    {
        $cfg = $this->config($tipo);
        $ordenarPor = $cfg['fields'][0]['name'] ?? 'id';
        $registros = $cfg['model']::orderBy($ordenarPor)->paginate(20);
        return view('cadastros.index', compact('tipo', 'cfg', 'registros'));
    }

    public function create(string $tipo)
    {
        $cfg = $this->config($tipo);
        $registro = null;
        return view('cadastros.form', compact('tipo', 'cfg', 'registro'));
    }

    public function store(Request $request, string $tipo)
    {
        $cfg = $this->config($tipo);
        $data = $request->validate($this->rules($cfg));
        $data = $this->withBooleans($request, $cfg, $data, true);
        $cfg['model']::create($data);
        return redirect()->route('cadastros.index', $tipo)->with('success', $cfg['titulo'] . ' criado(a) com sucesso.');
    }

    public function edit(string $tipo, int $id)
    {
        $cfg = $this->config($tipo);
        $registro = $cfg['model']::findOrFail($id);
        return view('cadastros.form', compact('tipo', 'cfg', 'registro'));
    }

    public function update(Request $request, string $tipo, int $id)
    {
        $cfg = $this->config($tipo);
        $registro = $cfg['model']::findOrFail($id);
        $data = $request->validate($this->rules($cfg));
        $data = $this->withBooleans($request, $cfg, $data, false);
        $registro->update($data);
        return redirect()->route('cadastros.index', $tipo)->with('success', $cfg['titulo'] . ' atualizado(a) com sucesso.');
    }

    public function destroy(string $tipo, int $id)
    {
        $cfg = $this->config($tipo);
        $cfg['model']::findOrFail($id)->delete();
        return redirect()->route('cadastros.index', $tipo)->with('success', $cfg['titulo'] . ' removido(a) com sucesso.');
    }

    private function rules(array $cfg): array
    {
        $rules = [];
        foreach ($cfg['fields'] as $f) {
            if ($f['type'] === 'boolean') {
                $rules[$f['name']] = 'nullable|boolean';
                continue;
            }
            $r = [];
            $r[] = ($f['required'] ?? false) ? 'required' : 'nullable';
            $r[] = match ($f['type']) {
                'number' => 'numeric',
                default => 'string',
            };
            if ($f['type'] === 'text') {
                $r[] = 'max:255';
            }
            if ($f['type'] === 'select' && !empty($f['options'])) {
                $valores = array_map(fn ($k, $v) => is_int($k) ? $v : $k, array_keys($f['options']), $f['options']);
                $r[] = 'in:' . implode(',', $valores);
            }
            $rules[$f['name']] = implode('|', $r);
        }
        return $rules;
    }

    private function withBooleans(Request $request, array $cfg, array $data, bool $isCreate): array
    {
        // Se o model tem 'ativo' no fillable, controla via checkbox
        $model = new $cfg['model'];
        if (in_array('ativo', $model->getFillable())) {
            $data['ativo'] = $isCreate ? $request->boolean('ativo', true) : $request->boolean('ativo');
        }
        // Campos boolean declarados nos fields
        foreach ($cfg['fields'] as $f) {
            if ($f['type'] === 'boolean') {
                $data[$f['name']] = $request->boolean($f['name']);
            }
        }
        return $data;
    }
}
