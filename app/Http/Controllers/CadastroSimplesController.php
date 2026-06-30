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
            'areas' => ['model' => \App\Models\AreaConhecimento::class, 'titulo' => 'Área de Conhecimento', 'codigo' => 24, 'fields' => [
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
                ['name' => 'codigo', 'label' => 'Código', 'type' => 'text', 'required' => false],
            ]],
            'graus' => ['model' => \App\Models\Grau::class, 'titulo' => 'Grau', 'codigo' => 28, 'fields' => $nome],
            'habilitacoes' => ['model' => \App\Models\Habilitacao::class, 'titulo' => 'Habilitação', 'codigo' => 29, 'fields' => $nome],
            'modulos' => ['model' => \App\Models\Modulo::class, 'titulo' => 'Módulo', 'codigo' => 31, 'fields' => [
                ['name' => 'nome', 'label' => 'Nome', 'type' => 'text', 'required' => true],
                ['name' => 'ordem', 'label' => 'Ordem', 'type' => 'number', 'required' => false],
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
            'formas-ingresso' => ['model' => \App\Models\FormaIngresso::class, 'titulo' => 'Forma de Ingresso', 'codigo' => 21, 'fields' => $nome],
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
            // CRM
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
