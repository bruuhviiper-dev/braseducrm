<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Departamento;
use App\Models\GrupoOperador;
use App\Models\Funcao;
use App\Models\InstituicaoEnsino;
use App\Models\AreaConhecimento;
use App\Models\Grau;
use App\Models\Habilitacao;
use App\Models\Turno;
use App\Models\PeriodoLetivo;
use App\Models\FormaIngresso;
use App\Models\Curso;
use App\Models\Disciplina;
use App\Models\MatrizCurricular;
use App\Models\Turma;
use App\Models\Sala;
use App\Models\Pessoa;
use App\Models\Aluno;
use App\Models\Funil;
use App\Models\EtapaFunil;
use App\Models\OrigemInteressado;
use App\Models\CategoriaInteressado;
use App\Models\EventoCrm;
use App\Models\MotivoPerda;
use App\Models\MotivoGanho;
use App\Models\TagCrm;
use App\Models\CategoriaReceber;
use App\Models\CategoriaPagar;
use App\Models\ContaBancaria;
use App\Models\PlanoContas;
use App\Models\Interessado;
use App\Models\Oportunidade;
use App\Models\Atividade;
use App\Models\Deposito;
use App\Models\CategoriaEstoque;
use App\Models\UnidadeMedida;
use App\Models\Notificacao;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $depAdmin = Departamento::create(['nome' => 'Administrativo']);
        $depAcad = Departamento::create(['nome' => 'Academico']);
        Departamento::create(['nome' => 'Financeiro']);
        $depCom = Departamento::create(['nome' => 'Comercial']);
        Departamento::create(['nome' => 'TI']);

        $grupoAdmin = GrupoOperador::create(['nome' => 'Administrador', 'descricao' => 'Acesso total ao sistema']);
        $grupoSec = GrupoOperador::create(['nome' => 'Secretaria', 'descricao' => 'Acesso academico e administrativo']);
        GrupoOperador::create(['nome' => 'Financeiro', 'descricao' => 'Acesso financeiro']);
        $grupoCom = GrupoOperador::create(['nome' => 'Consultor Comercial', 'descricao' => 'Acesso ao CRM']);

        $funcoes = [
            ['codigo' => 1, 'nome' => 'Lancamento de Avaliacao', 'modulo' => 'academico'],
            ['codigo' => 2, 'nome' => 'Calculo do Boletim', 'modulo' => 'academico'],
            ['codigo' => 5, 'nome' => 'Tabela de Avaliacao', 'modulo' => 'academico'],
            ['codigo' => 11, 'nome' => 'Cadastro de Pessoa', 'modulo' => 'administrativo'],
            ['codigo' => 17, 'nome' => 'Cadastro de Aluno', 'modulo' => 'administrativo'],
            ['codigo' => 23, 'nome' => 'Matricula e Historico', 'modulo' => 'academico'],
            ['codigo' => 25, 'nome' => 'Cadastro de Curso', 'modulo' => 'academico'],
            ['codigo' => 26, 'nome' => 'Cadastro de Disciplina', 'modulo' => 'academico'],
            ['codigo' => 30, 'nome' => 'Cadastro de Matriz Curricular', 'modulo' => 'academico'],
            ['codigo' => 38, 'nome' => 'Cadastro de Periodo Letivo', 'modulo' => 'academico'],
            ['codigo' => 40, 'nome' => 'Cadastro de Turma', 'modulo' => 'academico'],
            ['codigo' => 41, 'nome' => 'Montagem de Turma e Horario', 'modulo' => 'academico'],
            ['codigo' => 50, 'nome' => 'Cadastro do Plano de Contas', 'modulo' => 'financeiro'],
            ['codigo' => 52, 'nome' => 'Manutencao de Titulos a Pagar', 'modulo' => 'financeiro'],
            ['codigo' => 64, 'nome' => 'Manutencao de Titulos a Receber', 'modulo' => 'financeiro'],
            ['codigo' => 78, 'nome' => 'Fluxo de Caixa (Mensal)', 'modulo' => 'financeiro'],
            ['codigo' => 87, 'nome' => 'Templates de Mensagens', 'modulo' => 'comunicacao'],
            ['codigo' => 108, 'nome' => 'Cadastro de Interessados (CRM)', 'modulo' => 'crm'],
            ['codigo' => 109, 'nome' => 'Manutencao de Oportunidades (CRM)', 'modulo' => 'crm'],
            ['codigo' => 110, 'nome' => 'Funil de Oportunidades (CRM)', 'modulo' => 'crm'],
            ['codigo' => 142, 'nome' => 'Painel Comercial Geral', 'modulo' => 'crm'],
            ['codigo' => 148, 'nome' => 'Cadastro de Produtos de Estoque', 'modulo' => 'estoque'],
            ['codigo' => 152, 'nome' => 'Cadastro de Curso (EAD)', 'modulo' => 'ead'],
            ['codigo' => 167, 'nome' => 'Configuracao do Academico', 'modulo' => 'academico'],
            ['codigo' => 190, 'nome' => 'Desempenho Individual do Consultor', 'modulo' => 'crm'],
            ['codigo' => 200, 'nome' => 'Cadastro de Funil de Oportunidades', 'modulo' => 'crm'],
            ['codigo' => 214, 'nome' => 'Cadastro de Avaliacoes EAD', 'modulo' => 'ead'],
        ];
        foreach ($funcoes as $f) {
            Funcao::create($f);
        }

        // ============ USUARIOS ============
        // >>> LOGIN: admin  |  SENHA: admin123 <<<
        $admin = User::create([
            'nome' => 'Administrador',
            'login' => 'admin',
            'email' => 'admin@braseducrm.com',
            'password' => Hash::make('admin123'),
            'grupo_operador_id' => $grupoAdmin->id,
            'departamento_id' => $depAdmin->id,
            'ativo' => true,
        ]);

        $consultor1 = User::create([
            'nome' => 'Jessica Cristina Cavalheiro Ferrari',
            'login' => 'jessica',
            'email' => 'jessica@braseducrm.com',
            'password' => Hash::make('123456'),
            'grupo_operador_id' => $grupoCom->id,
            'departamento_id' => $depCom->id,
            'ativo' => true,
        ]);

        $consultor2 = User::create([
            'nome' => 'Carlos Henrique Silva',
            'login' => 'carlos',
            'email' => 'carlos@braseducrm.com',
            'password' => Hash::make('123456'),
            'grupo_operador_id' => $grupoCom->id,
            'departamento_id' => $depCom->id,
            'ativo' => true,
        ]);

        User::create([
            'nome' => 'Maria Santos',
            'login' => 'maria',
            'email' => 'maria@braseducrm.com',
            'password' => Hash::make('123456'),
            'grupo_operador_id' => $grupoSec->id,
            'departamento_id' => $depAcad->id,
            'ativo' => true,
        ]);

        $funcoesObj = Funcao::all();
        $favCodigos = [25, 26, 30, 40, 41, 23, 64, 110, 109, 190, 167, 11, 148];
        foreach ($favCodigos as $i => $cod) {
            $funcao = $funcoesObj->where('codigo', $cod)->first();
            if ($funcao) {
                $admin->favoritos()->attach($funcao->id, ['ordem' => $i]);
            }
        }

        // ============ ACADEMICO ============
        $inst = InstituicaoEnsino::create([
            'nome' => 'BrasEdu Instituicao de Ensino',
            'cnpj' => '12.345.678/0001-90',
            'razao_social' => 'BrasEdu Educacao Ltda',
            'endereco' => 'Av. Paulista, 1000',
            'cidade' => 'Sao Paulo',
            'uf' => 'SP',
            'cep' => '01310-100',
            'telefone' => '(11) 3000-0000',
            'email' => 'contato@brasedu.com.br',
        ]);

        $areas = collect(['Ciencias Exatas', 'Ciencias Humanas', 'Ciencias da Saude', 'Engenharias', 'Ciencias Sociais Aplicadas'])
            ->map(fn($n) => AreaConhecimento::create(['nome' => $n]));

        $graus = collect(['Graduacao', 'Pos-Graduacao', 'Mestrado', 'Doutorado', 'Livre', 'Tecnico'])
            ->map(fn($n) => Grau::create(['nome' => $n]));

        $habs = collect(['Bacharel', 'Licenciado(a)', 'Tecnologo(a)', 'Especialista', 'Mestre', 'Doutor(a)'])
            ->map(fn($n) => Habilitacao::create(['nome' => $n]));

        $turnos = collect(['Matutino', 'Vespertino', 'Noturno', 'Integral', 'EAD'])
            ->map(fn($n) => Turno::create(['nome' => $n]));

        $periodo = PeriodoLetivo::create(['nome' => '2024/1', 'data_inicio' => '2024-02-01', 'data_fim' => '2024-06-30']);
        PeriodoLetivo::create(['nome' => '2024/2', 'data_inicio' => '2024-08-01', 'data_fim' => '2024-12-15']);

        collect(['Vestibular', 'ENEM', 'Transferencia', 'Reingresso', 'Processo Seletivo'])
            ->each(fn($n) => FormaIngresso::create(['nome' => $n]));

        collect(['Sala 101', 'Sala 102', 'Sala 103', 'Sala 201', 'Laboratorio 1', 'Auditorio'])
            ->each(fn($n) => Sala::create(['nome' => $n, 'capacidade' => rand(20, 80)]));

        $cursoAdm = Curso::create([
            'nome' => 'Administracao', 'sigla' => 'ADM',
            'area_conhecimento_id' => $areas[4]->id, 'grau_id' => $graus[0]->id, 'habilitacao_id' => $habs[0]->id,
            'instituicao_ensino_id' => $inst->id, 'carga_horaria_total' => 3200, 'duracao_meses' => 48,
        ]);
        $cursoDireito = Curso::create([
            'nome' => 'Direito', 'sigla' => 'DIR',
            'area_conhecimento_id' => $areas[4]->id, 'grau_id' => $graus[0]->id, 'habilitacao_id' => $habs[0]->id,
            'instituicao_ensino_id' => $inst->id, 'carga_horaria_total' => 3700, 'duracao_meses' => 60,
        ]);
        Curso::create([
            'nome' => 'Engenharia Civil', 'sigla' => 'ENG',
            'area_conhecimento_id' => $areas[3]->id, 'grau_id' => $graus[0]->id, 'habilitacao_id' => $habs[0]->id,
            'instituicao_ensino_id' => $inst->id, 'carga_horaria_total' => 3600, 'duracao_meses' => 60,
        ]);
        Curso::create([
            'nome' => 'Enfermagem', 'sigla' => 'ENF',
            'area_conhecimento_id' => $areas[2]->id, 'grau_id' => $graus[0]->id, 'habilitacao_id' => $habs[0]->id,
            'instituicao_ensino_id' => $inst->id, 'carga_horaria_total' => 4000, 'duracao_meses' => 60,
        ]);

        $discs = collect([
            'Introducao a Administracao', 'Matematica Financeira', 'Contabilidade Geral',
            'Direito Empresarial', 'Marketing', 'Gestao de Pessoas', 'Economia',
            'Estatistica', 'Logistica', 'Empreendedorismo', 'Ingles Instrumental',
            'Metodologia Cientifica', 'Etica Profissional', 'TCC',
        ])->map(fn($n) => Disciplina::create(['nome' => $n, 'carga_horaria' => rand(40, 80)]));

        $matrizAdm = MatrizCurricular::create([
            'nome' => 'Matriz ADM 2024', 'curso_id' => $cursoAdm->id, 'carga_horaria_total' => 3200, 'situacao' => 'ativa',
        ]);
        foreach ($discs->take(8) as $i => $disc) {
            $matrizAdm->disciplinas()->attach($disc->id, ['carga_horaria' => $disc->carga_horaria, 'ordem' => $i]);
        }

        $matrizDir = MatrizCurricular::create([
            'nome' => 'Matriz DIR 2024', 'curso_id' => $cursoDireito->id, 'carga_horaria_total' => 3700, 'situacao' => 'ativa',
        ]);

        Turma::create([
            'nome' => 'ADM 2024.1 - Noturno', 'codigo' => 'ADM-2024-1N',
            'curso_id' => $cursoAdm->id, 'matriz_curricular_id' => $matrizAdm->id, 'turno_id' => $turnos[2]->id,
            'periodo_letivo_id' => $periodo->id, 'instituicao_ensino_id' => $inst->id,
            'data_inicio' => '2024-02-01', 'data_fim' => '2024-06-30', 'vagas' => 40, 'situacao' => 'em_andamento',
        ]);
        Turma::create([
            'nome' => 'DIR 2024.1 - Noturno', 'codigo' => 'DIR-2024-1N',
            'curso_id' => $cursoDireito->id, 'matriz_curricular_id' => $matrizDir->id, 'turno_id' => $turnos[2]->id,
            'periodo_letivo_id' => $periodo->id, 'instituicao_ensino_id' => $inst->id,
            'data_inicio' => '2024-02-01', 'data_fim' => '2024-06-30', 'vagas' => 50, 'situacao' => 'em_andamento',
        ]);

        // ============ PESSOAS E ALUNOS ============
        $pessoasData = [
            ['nome' => 'Joao Pedro Oliveira', 'cpf' => '111.222.333-44', 'email' => 'joao@email.com', 'data_nascimento' => '2000-05-15', 'sexo' => 'M', 'celular' => '(11) 99999-1111', 'cidade' => 'Sao Paulo', 'uf' => 'SP'],
            ['nome' => 'Ana Clara Santos', 'cpf' => '222.333.444-55', 'email' => 'ana@email.com', 'data_nascimento' => '2001-03-22', 'sexo' => 'F', 'celular' => '(11) 99999-2222', 'cidade' => 'Sao Paulo', 'uf' => 'SP'],
            ['nome' => 'Lucas Ferreira Lima', 'cpf' => '333.444.555-66', 'email' => 'lucas@email.com', 'data_nascimento' => '1999-11-08', 'sexo' => 'M', 'celular' => '(11) 99999-3333', 'cidade' => 'Guarulhos', 'uf' => 'SP'],
            ['nome' => 'Maria Eduarda Costa', 'cpf' => '444.555.666-77', 'email' => 'meduarda@email.com', 'data_nascimento' => '2002-07-30', 'sexo' => 'F', 'celular' => '(11) 99999-4444', 'cidade' => 'Osasco', 'uf' => 'SP'],
            ['nome' => 'Pedro Henrique Souza', 'cpf' => '555.666.777-88', 'email' => 'pedro@email.com', 'data_nascimento' => '2000-09-12', 'sexo' => 'M', 'celular' => '(11) 99999-5555', 'cidade' => 'Campinas', 'uf' => 'SP'],
            ['nome' => 'Gabriela Martins', 'cpf' => '666.777.888-99', 'email' => 'gabi@email.com', 'data_nascimento' => '2001-01-25', 'sexo' => 'F', 'celular' => '(11) 99999-6666', 'cidade' => 'Santos', 'uf' => 'SP'],
            ['nome' => 'Rafael Almeida', 'cpf' => '777.888.999-00', 'email' => 'rafael@email.com', 'data_nascimento' => '1998-04-18', 'sexo' => 'M', 'celular' => '(11) 99999-7777', 'cidade' => 'Sao Paulo', 'uf' => 'SP'],
            ['nome' => 'Camila Rodrigues', 'cpf' => '888.999.000-11', 'email' => 'camila@email.com', 'data_nascimento' => '2000-12-03', 'sexo' => 'F', 'celular' => '(11) 99999-8888', 'cidade' => 'Sao Paulo', 'uf' => 'SP'],
        ];
        $pessoas = collect($pessoasData)->map(fn($pd) => Pessoa::create($pd));

        $formaVest = FormaIngresso::where('nome', 'Vestibular')->first();
        foreach ($pessoas->take(5) as $i => $pessoa) {
            Aluno::create([
                'pessoa_id' => $pessoa->id,
                'ra' => '2024' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'forma_ingresso_id' => $formaVest->id,
                'data_ingresso' => '2024-02-01',
            ]);
        }

        // ============ CRM ============
        $origens = collect(['Site', 'Instagram', 'Facebook', 'Indicacao', 'Google', 'Evento', 'WhatsApp', 'Ligacao'])
            ->map(fn($n) => OrigemInteressado::create(['nome' => $n]));

        collect(['Graduacao', 'Pos-Graduacao', 'Cursos Livres', 'MBA'])
            ->each(fn($n) => CategoriaInteressado::create(['nome' => $n]));

        collect(['Ligacao', 'Email', 'WhatsApp', 'Reuniao', 'Visita', 'Proposta Enviada'])
            ->each(fn($n) => EventoCrm::create(['nome' => $n, 'cor' => sprintf('#%06X', mt_rand(0, 0xFFFFFF))]));

        collect(['Valor alto', 'Escolheu outra instituicao', 'Nao atendeu', 'Desistiu', 'Sem interesse'])
            ->each(fn($n) => MotivoPerda::create(['nome' => $n]));

        collect(['Preco competitivo', 'Qualidade do curso', 'Localizacao', 'Indicacao', 'Bolsa/Desconto'])
            ->each(fn($n) => MotivoGanho::create(['nome' => $n]));

        collect([['Urgente', '#EF4444'], ['VIP', '#8B5CF6'], ['Bolsista', '#F59E0B'], ['Indicado', '#10B981']])
            ->each(fn($t) => TagCrm::create(['nome' => $t[0], 'cor' => $t[1]]));

        // Doc CRM: fases da venda do Funil 110 (GANHO e PERDA são colunas fixas, não etapas)
        $funil = Funil::create(['nome' => 'FUNIL DE VENDAS - COMERCIAL', 'padrao' => true]);
        $etapas = collect([
            ['nome' => 'Sem Contato', 'cor' => '#6B7280', 'ordem' => 1, 'prazo_dias' => 1],
            ['nome' => 'Primeiro Contato', 'cor' => '#3B82F6', 'ordem' => 2, 'prazo_dias' => 5],
            ['nome' => 'Em Negociação', 'cor' => '#F59E0B', 'ordem' => 3, 'prazo_dias' => 15],
        ])->map(fn($ed) => EtapaFunil::create(array_merge($ed, ['funil_id' => $funil->id])));

        $cursos = Curso::all();
        $interData = [
            ['nome' => 'Fernando Gomes', 'email' => 'fernando@email.com', 'telefone' => '(11) 98000-1111'],
            ['nome' => 'Patricia Ribeiro', 'email' => 'patricia@email.com', 'telefone' => '(11) 98000-2222'],
            ['nome' => 'Marcos Oliveira', 'email' => 'marcos@email.com', 'telefone' => '(11) 98000-3333'],
            ['nome' => 'Juliana Pereira', 'email' => 'juliana@email.com', 'telefone' => '(11) 98000-4444'],
            ['nome' => 'Roberto Carlos Neto', 'email' => 'roberto@email.com', 'telefone' => '(11) 98000-5555'],
            ['nome' => 'Amanda Lopes', 'email' => 'amanda@email.com', 'telefone' => '(11) 98000-6666'],
            ['nome' => 'Thiago Mendes', 'email' => 'thiago@email.com', 'telefone' => '(11) 98000-7777'],
            ['nome' => 'Larissa Souza', 'email' => 'larissa@email.com', 'telefone' => '(11) 98000-8888'],
            ['nome' => 'Bruno Nascimento', 'email' => 'bruno@email.com', 'telefone' => '(11) 98000-9999'],
            ['nome' => 'Isabela Fernandes', 'email' => 'isabela@email.com', 'telefone' => '(11) 98001-0000'],
        ];

        foreach ($interData as $i => $id) {
            $inter = Interessado::create(array_merge($id, [
                'origem_id' => $origens->random()->id,
                'curso_id' => $cursos->random()->id,
            ]));
            Oportunidade::create([
                'interessado_id' => $inter->id,
                'funil_id' => $funil->id,
                'etapa_funil_id' => $etapas[$i % count($etapas)]->id,
                'consultor_id' => $i % 2 == 0 ? $consultor1->id : $consultor2->id,
                'curso_id' => $inter->curso_id,
                'titulo' => 'Interesse em ' . $cursos->find($inter->curso_id)->nome,
                'valor' => rand(500, 3000) * 1.0,
                'situacao' => 'aberta',
                'data_previsao_fechamento' => now()->addDays(rand(5, 30)),
            ]);
        }

        // ============ FINANCEIRO ============
        foreach (['Mensalidade', 'Matricula', 'Taxa', 'Requerimento'] as $n) {
            CategoriaReceber::create(['nome' => $n]);
        }
        foreach (['Energia', 'Agua', 'Aluguel', 'Telefone', 'Internet', 'Salarios', 'Material'] as $n) {
            CategoriaPagar::create(['nome' => $n]);
        }

        ContaBancaria::create([
            'nome' => 'Conta Principal - Banco do Brasil', 'banco' => 'Banco do Brasil',
            'agencia' => '1234-5', 'conta' => '12345-6', 'tipo_conta' => 'Corrente', 'saldo_inicial' => 50000,
        ]);
        ContaBancaria::create([
            'nome' => 'Caixa Interno', 'banco' => 'Caixa', 'tipo_conta' => 'Caixa', 'saldo_inicial' => 5000,
        ]);

        $receitas = PlanoContas::create(['codigo' => '1', 'nome' => 'Receitas', 'tipo' => 'sintetica', 'natureza' => 'receita', 'nivel' => 1]);
        PlanoContas::create(['codigo' => '1.1', 'nome' => 'Mensalidades', 'pai_id' => $receitas->id, 'natureza' => 'receita', 'nivel' => 2]);
        PlanoContas::create(['codigo' => '1.2', 'nome' => 'Taxas e Matriculas', 'pai_id' => $receitas->id, 'natureza' => 'receita', 'nivel' => 2]);

        $despesas = PlanoContas::create(['codigo' => '2', 'nome' => 'Despesas', 'tipo' => 'sintetica', 'natureza' => 'despesa', 'nivel' => 1]);
        PlanoContas::create(['codigo' => '2.1', 'nome' => 'Folha de Pagamento', 'pai_id' => $despesas->id, 'natureza' => 'despesa', 'nivel' => 2]);
        PlanoContas::create(['codigo' => '2.2', 'nome' => 'Servicos e Utilidades', 'pai_id' => $despesas->id, 'natureza' => 'despesa', 'nivel' => 2]);

        // ============ ESTOQUE ============
        foreach (['Material de Escritorio', 'Limpeza', 'Didatico', 'Informatica', 'Outros'] as $n) {
            CategoriaEstoque::create(['nome' => $n]);
        }
        foreach ([['nome' => 'Unidade', 'sigla' => 'UN'], ['nome' => 'Caixa', 'sigla' => 'CX'], ['nome' => 'Pacote', 'sigla' => 'PCT'], ['nome' => 'Litro', 'sigla' => 'L'], ['nome' => 'Quilo', 'sigla' => 'KG']] as $u) {
            UnidadeMedida::create($u);
        }
        Deposito::create(['nome' => 'Deposito Principal', 'ativo' => true]);
        Deposito::create(['nome' => 'Almoxarifado', 'ativo' => true]);

        // ============ NOTIFICACOES ============
        Notificacao::create([
            'user_id' => $admin->id,
            'titulo' => 'Bem-vindo ao One!',
            'mensagem' => 'Sistema configurado e pronto para uso.',
            'tipo' => 'success',
            'lida' => false,
        ]);
        Notificacao::create([
            'user_id' => $admin->id,
            'titulo' => 'Novas matriculas pendentes',
            'mensagem' => '3 alunos aguardando confirmacao de matricula.',
            'tipo' => 'info',
            'lida' => false,
        ]);

        // ============ ATIVIDADES ============
        for ($i = 0; $i < 7; $i++) {
            Atividade::create([
                'user_id' => $admin->id,
                'titulo' => 'Inconsistencia no titulo',
                'descricao' => 'Verificar inconsistencia - ' . $pessoasData[array_rand($pessoasData)]['nome'],
                'data_vencimento' => now()->subHours(rand(1, 48)),
                'situacao' => 'atrasada',
            ]);
        }
        for ($i = 0; $i < 2; $i++) {
            Atividade::create([
                'user_id' => $admin->id,
                'titulo' => 'Acompanhar matricula',
                'descricao' => 'Verificar documentacao pendente',
                'data_vencimento' => now()->addDays(rand(1, 7)),
                'situacao' => 'pendente',
            ]);
        }
    }
}
