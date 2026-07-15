<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\EmissaoController;
use App\Http\Controllers\PainelController;
use App\Http\Controllers\RequerimentoController;
use App\Http\Controllers\AtendimentoController;
use App\Http\Controllers\Academico\CursoController;
use App\Http\Controllers\Academico\DisciplinaController;
use App\Http\Controllers\Academico\MatrizCurricularController;
use App\Http\Controllers\Academico\TurmaController;
use App\Http\Controllers\Academico\MatriculaController;
use App\Http\Controllers\Academico\PeriodoLetivoController;
use App\Http\Controllers\Academico\TurnoController;
use App\Http\Controllers\Academico\SalaController;
use App\Http\Controllers\Academico\TabelaAvaliacaoController;
use App\Http\Controllers\Academico\ConfiguracaoBoletimController;
use App\Http\Controllers\Academico\LancamentoNotaController;
use App\Http\Controllers\Academico\FrequenciaController;
use App\Http\Controllers\Academico\BoletimController;
use App\Http\Controllers\Academico\MontagemTurmaController;
use App\Http\Controllers\Crm\InteressadoController;
use App\Http\Controllers\Crm\FunilController;
use App\Http\Controllers\Crm\OportunidadeController;
use App\Http\Controllers\Crm\DesempenhoController;
use App\Http\Controllers\Crm\OrigemController;
use App\Http\Controllers\Crm\TagCrmController;
use App\Http\Controllers\Crm\EventoCrmController;
use App\Http\Controllers\Crm\MetaCrmController;
use App\Http\Controllers\Crm\ConfiguracaoCrmController;
use App\Http\Controllers\Financeiro\TituloReceberController;
use App\Http\Controllers\Financeiro\TituloPagarController;
use App\Http\Controllers\Financeiro\PlanoContasController;
use App\Http\Controllers\Financeiro\FluxoCaixaController;
use App\Http\Controllers\Financeiro\CategoriaReceberController;
use App\Http\Controllers\Financeiro\CategoriaPagarController;
use App\Http\Controllers\Financeiro\ContaBancariaController;
use App\Http\Controllers\Financeiro\DescontoController;
use App\Http\Controllers\Comunicacao\TemplateMensagemController;
use App\Http\Controllers\Estoque\ProdutoEstoqueController;
use App\Http\Controllers\Estoque\CategoriaEstoqueController;
use App\Http\Controllers\Estoque\UnidadeMedidaController;
use App\Http\Controllers\Estoque\MovimentacaoEstoqueController;
use App\Http\Controllers\Ead\CursoEadController;
use App\Http\Controllers\GeralController;
use App\Http\Controllers\GedController;
use App\Http\Controllers\IntegracoesController;
use App\Http\Controllers\MatriculaOnlineController;
use App\Http\Controllers\MatriculaOnline\AberturaController;
use App\Http\Controllers\MatriculaOnline\CupomController;
use App\Http\Controllers\MatriculaOnline\InscricaoController;
use App\Http\Controllers\PortaisController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\PainelClienteController;

// Landing Page (public)
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::post('/contato', [LandingController::class, 'contato'])->name('landing.contato');

// Link de pagamento público (230 — o aluno abre sem login; baixa automática ao pagar)
Route::get('/pagar/{token}', [\App\Http\Controllers\Financeiro\LinkPagamentoController::class, 'publico'])->name('pagamento.publico');
Route::post('/pagar/{token}', [\App\Http\Controllers\Financeiro\LinkPagamentoController::class, 'pagar'])->name('pagamento.publico.pagar');

// Link de matrícula online público (doc CRM: autoatendimento gerado no card; ao concluir, o card vai para Ganho)
Route::get('/m/{token}', [\App\Http\Controllers\Crm\MatriculaLinkController::class, 'publico'])->name('matricula-link');
Route::post('/m/{token}', [\App\Http\Controllers\Crm\MatriculaLinkController::class, 'inscrever'])->name('matricula-link.inscrever');

// Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil do usuário
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::put('/perfil/senha', [PerfilController::class, 'updatePassword'])->name('perfil.senha');

    // Notificações
    Route::get('/notificacoes', [NotificacaoController::class, 'index'])->name('notificacoes.index');
    Route::post('/notificacoes/{notificacao}/marcar-lida', [NotificacaoController::class, 'marcarLida'])->name('notificacoes.marcar-lida');

    // Calendário
    Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index');

    // Tickets / Suporte
    Route::resource('tickets', TicketController::class);
    Route::post('/tickets/{ticket}/responder', [TicketController::class, 'responder'])->name('tickets.responder');

    // Configurações
    Route::get('/configuracoes', [ConfiguracaoController::class, 'index'])->name('configuracoes.index');

    // Painel do Cliente
    Route::get('/painel-cliente', [PainelClienteController::class, 'index'])->name('painel-cliente.index');

    // Administrativo
    Route::post('pessoas/quick', [PessoaController::class, 'quickStore'])->name('pessoas.quick');
    Route::resource('pessoas', PessoaController::class);
    Route::post('pessoas/{pessoa}/contas', [PessoaController::class, 'adicionarConta'])->name('pessoas.contas.store');
    Route::delete('pessoas/{pessoa}/contas/{conta}', [PessoaController::class, 'removerConta'])->name('pessoas.contas.destroy');
    Route::post('pessoas/{pessoa}/anexos', [PessoaController::class, 'uploadAnexo'])->name('pessoas.anexos.store');
    Route::patch('pessoas/{pessoa}/anexos/{anexo}/aprovacao', [PessoaController::class, 'aprovacaoAnexo'])->name('pessoas.anexos.aprovacao');
    Route::post('alunos/quick', [AlunoController::class, 'quickStore'])->name('alunos.quick');
    Route::resource('alunos', AlunoController::class);
    Route::resource('documentos', DocumentoController::class)->parameters(['documentos' => 'documento'])->except('show');

    // Painéis com gráficos
    Route::get('paineis/comercial', [PainelController::class, 'comercial'])->name('paineis.comercial');
    Route::get('paineis/financeiro', [PainelController::class, 'financeiro'])->name('paineis.financeiro');
    Route::get('paineis/academico', [PainelController::class, 'academico'])->name('paineis.academico');

    // Emissões em PDF
    Route::get('emissoes/historico/{aluno}', [EmissaoController::class, 'historicoEscolar'])->name('emissoes.historico');
    Route::get('emissoes/declaracao/{matricula}', [EmissaoController::class, 'declaracaoMatricula'])->name('emissoes.declaracao');
    Route::get('emissoes/recibo/{titulo}', [EmissaoController::class, 'recibo'])->name('emissoes.recibo');
    Route::resource('requerimentos', RequerimentoController::class)->parameters(['requerimentos' => 'requerimento'])->except('show');
    Route::resource('atendimentos', AtendimentoController::class)->parameters(['atendimentos' => 'atendimento'])->except('show');
    Route::post('atendimentos/{atendimento}/interagir', [AtendimentoController::class, 'interagir'])->name('atendimentos.interagir');
    Route::resource('profissionais', \App\Http\Controllers\ProfissionalController::class)->parameters(['profissionais' => 'profissional'])->except('show');

    // Cadastros-base genéricos (tabelas de apoio)
    Route::prefix('cadastros')->name('cadastros.')->controller(\App\Http\Controllers\CadastroSimplesController::class)->group(function () {
        Route::get('{tipo}', 'index')->name('index');
        Route::get('{tipo}/novo', 'create')->name('create');
        Route::post('{tipo}', 'store')->name('store');
        Route::get('{tipo}/{id}/editar', 'edit')->name('edit');
        Route::put('{tipo}/{id}', 'update')->name('update');
        Route::delete('{tipo}/{id}', 'destroy')->name('destroy');
    });

    // Administrativo - controle de acesso
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('operadores', \App\Http\Controllers\Admin\OperadorController::class)->parameters(['operadores' => 'operador'])->except('show');
        Route::resource('grupos', \App\Http\Controllers\Admin\GrupoOperadorController::class)->parameters(['grupos' => 'grupo'])->except('show');
        Route::resource('departamentos', \App\Http\Controllers\Admin\DepartamentoController::class)->parameters(['departamentos' => 'departamento'])->except('show');

        // Catálogo de Permissões (docs): por departamento + liberações extras por usuário
        Route::get('departamentos/{departamento}/permissoes', [\App\Http\Controllers\Admin\PermissaoController::class, 'departamento'])->name('departamentos.permissoes');
        Route::post('departamentos/{departamento}/permissoes', [\App\Http\Controllers\Admin\PermissaoController::class, 'salvarDepartamento'])->name('departamentos.permissoes.salvar');
        Route::get('operadores/{operador}/permissoes', [\App\Http\Controllers\Admin\PermissaoController::class, 'usuario'])->name('operadores.permissoes');
        Route::post('operadores/{operador}/permissoes', [\App\Http\Controllers\Admin\PermissaoController::class, 'salvarUsuario'])->name('operadores.permissoes.salvar');
    });

    // Academico
    Route::prefix('academico')->name('academico.')->group(function () {
        Route::resource('cursos', CursoController::class);
        Route::resource('disciplinas', DisciplinaController::class);
        Route::resource('matrizes', MatrizCurricularController::class);
        Route::resource('turmas', TurmaController::class);
        Route::post('matriculas/{matricula}/cancelar', [\App\Http\Controllers\Academico\MatriculaController::class, 'cancelar'])->name('matriculas.cancelar');
        // Wizard "Matrícula e Histórico" em 4 passos (doc CRM: aberto ao dar Ganho no card)
        Route::get('matriculas/nova', [\App\Http\Controllers\Academico\MatriculaWizardController::class, 'create'])->name('matriculas.wizard');
        Route::post('matriculas/nova', [\App\Http\Controllers\Academico\MatriculaWizardController::class, 'store'])->name('matriculas.wizard.store');
        Route::resource('matriculas', MatriculaController::class);
        Route::get('matriculas/{matricula}/historico', [\App\Http\Controllers\Academico\HistoricoEscolarController::class, 'editar'])->name('matriculas.historico');
        Route::put('matriculas/{matricula}/historico', [\App\Http\Controllers\Academico\HistoricoEscolarController::class, 'salvar'])->name('matriculas.historico.salvar');
        // Ficha "Matrícula e Histórico" (23) fiel ao EDUQ
        Route::get('matriculas/{matricula}/ficha', [\App\Http\Controllers\Academico\FichaMatriculaController::class, 'ficha'])->name('matriculas.ficha');
        Route::put('matriculas/{matricula}/ficha', [\App\Http\Controllers\Academico\FichaMatriculaController::class, 'salvar'])->name('matriculas.ficha.salvar');
        Route::post('matriculas/{matricula}/ficha/enturmar', [\App\Http\Controllers\Academico\FichaMatriculaController::class, 'enturmar'])->name('matriculas.ficha.enturmar');
        Route::delete('matriculas/{matricula}/ficha/enturmacoes/{enturmacao}', [\App\Http\Controllers\Academico\FichaMatriculaController::class, 'desenturmar'])->name('matriculas.ficha.desenturmar');
        Route::post('matriculas/{matricula}/ficha/transferir', [\App\Http\Controllers\Academico\FichaMatriculaController::class, 'transferirTurma'])->name('matriculas.ficha.transferir');
        Route::post('matriculas/{matricula}/ficha/aprovar-documentos', [\App\Http\Controllers\Academico\FichaMatriculaController::class, 'aprovarDocumentos'])->name('matriculas.ficha.aprovar-documentos');
        Route::post('matriculas/{matricula}/ficha/enade', [\App\Http\Controllers\Academico\FichaMatriculaController::class, 'enadeAdicionar'])->name('matriculas.ficha.enade');
        Route::delete('matriculas/{matricula}/ficha/enade/{enade}', [\App\Http\Controllers\Academico\FichaMatriculaController::class, 'enadeRemover'])->name('matriculas.ficha.enade.remover');
        Route::post('matriculas/{matricula}/ficha/assinatura', [\App\Http\Controllers\Academico\FichaMatriculaController::class, 'assinaturaCriar'])->name('matriculas.ficha.assinatura');
        Route::delete('matriculas/{matricula}/ficha/assinatura/{assinatura}', [\App\Http\Controllers\Academico\FichaMatriculaController::class, 'assinaturaRemover'])->name('matriculas.ficha.assinatura.remover');
        Route::resource('periodos-letivos', PeriodoLetivoController::class);
        Route::resource('turnos', TurnoController::class);
        Route::resource('salas', SalaController::class);
        Route::resource('calendarios', \App\Http\Controllers\Academico\CalendarioController::class)->except('show');
        Route::resource('grades-horario', \App\Http\Controllers\Academico\GradeHorarioController::class)->parameters(['grades-horario' => 'grades_horario'])->except('show');

        // Plano de Ensino/Aula (P6 - lote 1)
        Route::resource('estruturas-plano', \App\Http\Controllers\Academico\EstruturaPlanoController::class)->parameters(['estruturas-plano' => 'estruturas_plano'])->except('show');
        Route::get('planos-ensino', [\App\Http\Controllers\Academico\PlanoEnsinoController::class, 'index'])->name('planos-ensino.index');
        Route::get('planos-ensino/{turma_montada}/{disciplina}', [\App\Http\Controllers\Academico\PlanoEnsinoController::class, 'preencher'])->name('planos-ensino.preencher');
        Route::put('planos-ensino/{turma_montada}/{disciplina}', [\App\Http\Controllers\Academico\PlanoEnsinoController::class, 'salvar'])->name('planos-ensino.salvar');

        // Controles de Matrícula e Frequência (P6 - lote 2)
        Route::resource('horas-complementares', \App\Http\Controllers\Academico\HoraComplementarController::class)->parameters(['horas-complementares' => 'horas_complementare'])->except('show');
        Route::post('horas-complementares/{horas_complementare}/aprovar', [\App\Http\Controllers\Academico\HoraComplementarController::class, 'aprovar'])->name('horas-complementares.aprovar');
        Route::resource('praticas-supervisionadas', \App\Http\Controllers\Academico\PraticaSupervisionadaController::class)->parameters(['praticas-supervisionadas' => 'praticas_supervisionada'])->except('show');
        Route::resource('liberacoes-frequencia', \App\Http\Controllers\Academico\LiberacaoFrequenciaController::class)->parameters(['liberacoes-frequencia' => 'liberacoes_frequencium'])->except('show');
        Route::get('programacoes-avaliacao', [\App\Http\Controllers\Academico\ProgramacaoAvaliacaoController::class, 'index'])->name('programacoes-avaliacao.index');
        Route::get('programacoes-avaliacao/{turma_montada}/{disciplina}', [\App\Http\Controllers\Academico\ProgramacaoAvaliacaoController::class, 'editar'])->name('programacoes-avaliacao.editar');
        Route::put('programacoes-avaliacao/{turma_montada}/{disciplina}', [\App\Http\Controllers\Academico\ProgramacaoAvaliacaoController::class, 'salvar'])->name('programacoes-avaliacao.salvar');

        // Emissões Acadêmicas em PDF (P6 - lote 3)
        Route::get('emissoes', [\App\Http\Controllers\Academico\AcademicoEmissaoController::class, 'index'])->name('emissoes.index');
        // 79 Emissão de Alunos Matriculados — construtor de relatório dinâmico (Layouts/Colunas/Filtros + PDF/CSV/XLSX)
        Route::get('emissoes/alunos-matriculados', [\App\Http\Controllers\Academico\EmissaoAlunosController::class, 'index'])->name('emissoes.alunos-matriculados');
        Route::get('emissoes/alunos-matriculados/emitir', [\App\Http\Controllers\Academico\EmissaoAlunosController::class, 'emitir'])->name('emissoes.alunos-matriculados.emitir');
        Route::post('emissoes/alunos-matriculados/layout', [\App\Http\Controllers\Academico\EmissaoAlunosController::class, 'salvarLayout'])->name('emissoes.alunos-matriculados.layout');
        Route::delete('emissoes/alunos-matriculados/layout/{layout}', [\App\Http\Controllers\Academico\EmissaoAlunosController::class, 'excluirLayout'])->name('emissoes.alunos-matriculados.layout.excluir');
        // Layouts genéricos das emissões (salvar/excluir) — usados pelo componente x-report-builder
        Route::post('emissoes/layout', [\App\Http\Controllers\Academico\EmissaoLayoutController::class, 'salvar'])->name('emissoes.layout');
        Route::delete('emissoes/layout/{layout}', [\App\Http\Controllers\Academico\EmissaoLayoutController::class, 'excluir'])->name('emissoes.layout.excluir');
        // 185 Horários Professores / 249 Pendências / 305 Disciplinas dos Alunos — report-builders
        Route::get('emissoes/horarios-professores', [\App\Http\Controllers\Academico\EmissaoHorariosController::class, 'index'])->name('emissoes.horarios-professores');
        Route::get('emissoes/horarios-professores/emitir', [\App\Http\Controllers\Academico\EmissaoHorariosController::class, 'emitir'])->name('emissoes.horarios-professores.emitir');
        Route::get('emissoes/pendencias-notas-faltas', [\App\Http\Controllers\Academico\EmissaoPendenciasController::class, 'index'])->name('emissoes.pendencias-notas-faltas');
        Route::get('emissoes/pendencias-notas-faltas/emitir', [\App\Http\Controllers\Academico\EmissaoPendenciasController::class, 'emitir'])->name('emissoes.pendencias-notas-faltas.emitir');
        Route::get('emissoes/disciplinas-alunos', [\App\Http\Controllers\Academico\EmissaoDisciplinasAlunosController::class, 'index'])->name('emissoes.disciplinas-alunos');
        Route::get('emissoes/disciplinas-alunos/emitir', [\App\Http\Controllers\Academico\EmissaoDisciplinasAlunosController::class, 'emitir'])->name('emissoes.disciplinas-alunos.emitir');
        // 184 Emissão de Turmas Montadas — construtor de relatório dinâmico
        Route::get('emissoes/turmas-montadas', [\App\Http\Controllers\Academico\EmissaoTurmasController::class, 'index'])->name('emissoes.turmas-montadas');
        Route::get('emissoes/turmas-montadas/emitir', [\App\Http\Controllers\Academico\EmissaoTurmasController::class, 'emitir'])->name('emissoes.turmas-montadas.emitir');
        Route::post('emissoes/turmas-montadas/layout', [\App\Http\Controllers\Academico\EmissaoTurmasController::class, 'salvarLayout'])->name('emissoes.turmas-montadas.layout');
        Route::delete('emissoes/turmas-montadas/layout/{layout}', [\App\Http\Controllers\Academico\EmissaoTurmasController::class, 'excluirLayout'])->name('emissoes.turmas-montadas.layout.excluir');
        // 60 Emissão de Notas e Faltas — construtor de relatório dinâmico
        Route::get('emissoes/notas-faltas', [\App\Http\Controllers\Academico\EmissaoNotasFaltasController::class, 'index'])->name('emissoes.notas-faltas');
        Route::get('emissoes/notas-faltas/emitir', [\App\Http\Controllers\Academico\EmissaoNotasFaltasController::class, 'emitir'])->name('emissoes.notas-faltas.emitir');
        Route::get('emissoes/diario-classe', [\App\Http\Controllers\Academico\AcademicoEmissaoController::class, 'diarioClasse'])->name('emissoes.diario-classe');
        Route::get('emissoes/documentos', [\App\Http\Controllers\Academico\AcademicoEmissaoController::class, 'documentos'])->name('emissoes.documentos');
        Route::get('emissoes/matriz-curricular', [\App\Http\Controllers\Academico\AcademicoEmissaoController::class, 'matrizCurricular'])->name('emissoes.matriz-curricular');
        Route::get('emissoes/declaracao-aula', [\App\Http\Controllers\Academico\AcademicoEmissaoController::class, 'declaracaoAula'])->name('emissoes.declaracao-aula');

        // Painéis de ensino (P6 - lote 4c)
        Route::get('planejamento-diario', [\App\Http\Controllers\Academico\PainelEnsinoController::class, 'planejamentoDiario'])->name('planejamento-diario.index');
        Route::get('painel-professor', [\App\Http\Controllers\Academico\PainelEnsinoController::class, 'painelProfessor'])->name('painel-professor.index');

        // Documentos (P6 - lote 4a)
        Route::get('entregas-documento', [\App\Http\Controllers\Academico\EntregaDocumentoController::class, 'index'])->name('entregas-documento.index');
        Route::get('entregas-documento/{matricula}/gerenciar', [\App\Http\Controllers\Academico\EntregaDocumentoController::class, 'gerenciar'])->name('entregas-documento.gerenciar');
        Route::put('entregas-documento/{matricula}', [\App\Http\Controllers\Academico\EntregaDocumentoController::class, 'salvar'])->name('entregas-documento.salvar');
        Route::get('consulta-documentos', [\App\Http\Controllers\Academico\EntregaDocumentoController::class, 'consultaPendentes'])->name('entregas-documento.consulta');

        // Exame de Nível (183) e Rematrículas (279) (P6 - lote 4b)
        Route::resource('exames-nivel', \App\Http\Controllers\Academico\ExameNivelController::class)->parameters(['exames-nivel' => 'exames_nivel'])->except('show');
        Route::resource('rematriculas', \App\Http\Controllers\Academico\RematriculaController::class)->parameters(['rematriculas' => 'rematricula'])->except('show');
        Route::resource('tabelas-avaliacao', TabelaAvaliacaoController::class)->except('show');
        Route::resource('configuracoes-boletim', ConfiguracaoBoletimController::class)->except('show');
        Route::get('lancamento-notas', [LancamentoNotaController::class, 'index'])->name('lancamento-notas.index');
        Route::post('lancamento-notas', [LancamentoNotaController::class, 'salvar'])->name('lancamento-notas.salvar');
        Route::get('frequencia', [FrequenciaController::class, 'index'])->name('frequencia.index');
        Route::post('frequencia', [FrequenciaController::class, 'salvar'])->name('frequencia.salvar');
        Route::get('boletim', [BoletimController::class, 'index'])->name('boletim.index');
        Route::post('boletim/consolidar', [BoletimController::class, 'consolidar'])->name('boletim.consolidar');
        Route::get('exclusao-notas', [\App\Http\Controllers\Academico\ExclusaoNotasController::class, 'index'])->name('exclusao-notas.index');
        Route::post('exclusao-notas', [\App\Http\Controllers\Academico\ExclusaoNotasController::class, 'excluir'])->name('exclusao-notas.excluir');
        Route::get('historico-digital', [\App\Http\Controllers\Academico\HistoricoDigitalController::class, 'index'])->name('historico-digital.index');
        Route::get('historico-digital/{aluno}/pdf', [\App\Http\Controllers\Academico\HistoricoDigitalController::class, 'gerar'])->name('historico-digital.gerar');
        Route::get('configuracao', [\App\Http\Controllers\Academico\ConfiguracaoAcademicoController::class, 'index'])->name('configuracao.index');
        Route::put('configuracao', [\App\Http\Controllers\Academico\ConfiguracaoAcademicoController::class, 'update']);
        Route::resource('montagem-turma', MontagemTurmaController::class)->parameters(['montagem-turma' => 'montagem_turma'])->except('show');
        Route::post('montagem-turma/{montagem_turma}/matricular', [MontagemTurmaController::class, 'matricular'])->name('montagem-turma.matricular');
        Route::delete('montagem-turma/{montagem_turma}/desmatricular/{matricula}', [MontagemTurmaController::class, 'desmatricular'])->name('montagem-turma.desmatricular');
        Route::post('montagem-turma/{montagem_turma}/confirmar/{matricula}', [MontagemTurmaController::class, 'confirmar'])->name('montagem-turma.confirmar');
        Route::get('montagem-turma/{montagem_turma}/finalizar', [MontagemTurmaController::class, 'finalizar'])->name('montagem-turma.finalizar');
        Route::post('montagem-turma/{montagem_turma}/finalizar', [MontagemTurmaController::class, 'processarFinalizacao'])->name('montagem-turma.processar-finalizacao');
    });

    // CRM
    Route::prefix('crm')->name('crm.')->group(function () {
        Route::resource('interessados', InteressadoController::class);
        Route::resource('funil', FunilController::class);
        Route::resource('oportunidades', OportunidadeController::class);
        Route::patch('oportunidades/{oportunidade}/mover-etapa', [OportunidadeController::class, 'moverEtapa'])->name('oportunidades.mover-etapa');
        Route::post('oportunidades/{oportunidade}/ganhar', [OportunidadeController::class, 'ganhar'])->name('oportunidades.ganhar');
        Route::post('oportunidades/{oportunidade}/perder', [OportunidadeController::class, 'perder'])->name('oportunidades.perder');

        // Ficha do card (doc CRM): linha do tempo, atividades, interesses, estrelas e link de matrícula online
        Route::post('oportunidades/{oportunidade}/anotar', [OportunidadeController::class, 'anotar'])->name('oportunidades.anotar');
        Route::post('oportunidades/{oportunidade}/atividades', [OportunidadeController::class, 'agendarAtividade'])->name('oportunidades.atividades');
        Route::patch('oportunidades/{oportunidade}/atividades/{atividade}/concluir', [OportunidadeController::class, 'concluirAtividade'])->name('oportunidades.atividades.concluir');
        Route::patch('oportunidades/{oportunidade}/estrelas', [OportunidadeController::class, 'estrelas'])->name('oportunidades.estrelas');
        Route::post('oportunidades/{oportunidade}/interesses', [OportunidadeController::class, 'interesseAdicionar'])->name('oportunidades.interesses');
        Route::delete('oportunidades/{oportunidade}/interesses/{curso}', [OportunidadeController::class, 'interesseRemover'])->name('oportunidades.interesses.remover');
        Route::post('oportunidades/{oportunidade}/gerar-link', [OportunidadeController::class, 'gerarLink'])->name('oportunidades.gerar-link');
        Route::get('desempenho', [DesempenhoController::class, 'index'])->name('desempenho.index');
        Route::resource('origens', OrigemController::class)->parameters(['origens' => 'origem'])->except('show');
        Route::resource('tags', TagCrmController::class)->parameters(['tags' => 'tag'])->except('show');
        Route::resource('eventos', EventoCrmController::class)->parameters(['eventos' => 'evento'])->except('show');
        Route::resource('metas', MetaCrmController::class)->parameters(['metas' => 'meta'])->except('show');
        Route::get('configuracoes', [ConfiguracaoCrmController::class, 'index'])->name('configuracoes.index');
        Route::put('configuracoes', [ConfiguracaoCrmController::class, 'update'])->name('configuracoes.update');
        Route::post('configuracoes/redistribuir', [ConfiguracaoCrmController::class, 'redistribuir'])->name('configuracoes.redistribuir');

        // Propostas (201) e Exportação (159)
        Route::get('propostas', [\App\Http\Controllers\Crm\PropostaCrmController::class, 'index'])->name('propostas.index');
        Route::get('propostas/{oportunidade}/pdf', [\App\Http\Controllers\Crm\PropostaCrmController::class, 'gerar'])->name('propostas.gerar');
        Route::post('propostas/{oportunidade}', [\App\Http\Controllers\Crm\PropostaCrmController::class, 'store'])->name('propostas.store');
        Route::post('propostas/{proposta}/aprovar', [\App\Http\Controllers\Crm\PropostaCrmController::class, 'aprovar'])->name('propostas.aprovar');
        Route::get('exportacao', [\App\Http\Controllers\Crm\ExportacaoCrmController::class, 'index'])->name('exportacao.index');
        Route::get('exportacao/csv', [\App\Http\Controllers\Crm\ExportacaoCrmController::class, 'exportar'])->name('exportacao.csv');
    });

    // Financeiro
    Route::prefix('financeiro')->name('financeiro.')->group(function () {
        Route::get('titulos-receber/remessa', [TituloReceberController::class, 'gerarRemessa'])->name('titulos-receber.remessa');
        Route::post('titulos-receber/carregar', [TituloReceberController::class, 'carregar'])->name('titulos-receber.carregar');
        Route::post('titulos-receber/gerar', [TituloReceberController::class, 'gerar'])->name('titulos-receber.gerar');
        Route::resource('titulos-receber', TituloReceberController::class);
        Route::post('titulos-receber/{titulo}/baixar', [TituloReceberController::class, 'baixar'])->name('titulos-receber.baixar');
        Route::post('titulos-receber/{titulo}/estornar', [TituloReceberController::class, 'estornar'])->name('titulos-receber.estornar');
        Route::post('titulos-receber/{titulo}/anotar', [TituloReceberController::class, 'anotar'])->name('titulos-receber.anotar');
        Route::patch('titulos-receber/{titulo}/baixado-por', [TituloReceberController::class, 'alterarBaixadoPor'])->name('titulos-receber.baixado-por');
        Route::resource('titulos-pagar', TituloPagarController::class);
        Route::resource('plano-contas', PlanoContasController::class);
        Route::get('fluxo-caixa', [FluxoCaixaController::class, 'index'])->name('fluxo-caixa.index');
        Route::resource('categorias-receber', CategoriaReceberController::class)->except('show');
        Route::resource('categorias-pagar', CategoriaPagarController::class)->except('show');
        Route::resource('contas-bancarias', ContaBancariaController::class)->except('show');
        Route::resource('descontos', DescontoController::class)->parameters(['descontos' => 'desconto'])->except('show');
        Route::resource('descontos-condicionais', \App\Http\Controllers\Financeiro\DescontoCondicionalController::class)->parameters(['descontos-condicionais' => 'desconto'])->except('show');
        Route::get('configuracao', [\App\Http\Controllers\Financeiro\ConfiguracaoFinanceiroController::class, 'index'])->name('configuracao.index');
        Route::put('configuracao', [\App\Http\Controllers\Financeiro\ConfiguracaoFinanceiroController::class, 'update']);
        Route::post('configuracao/processar-reguas', [\App\Http\Controllers\Financeiro\ConfiguracaoFinanceiroController::class, 'processarReguas'])->name('configuracao.processar-reguas');
        Route::get('nfse', [\App\Http\Controllers\Financeiro\ConfiguracaoNfseController::class, 'index'])->name('nfse.index');
        Route::put('nfse', [\App\Http\Controllers\Financeiro\ConfiguracaoNfseController::class, 'update'])->name('nfse.update');

        // Emissões financeiras (173/66/113/106/93/101/180)
        Route::get('emissoes', [\App\Http\Controllers\Financeiro\FinanceiroEmissaoController::class, 'index'])->name('emissoes.index');
        Route::get('emissoes/titulos-pagar', [\App\Http\Controllers\Financeiro\FinanceiroEmissaoController::class, 'titulosPagar'])->name('emissoes.titulos-pagar');
        Route::get('emissoes/boletos', [\App\Http\Controllers\Financeiro\FinanceiroEmissaoController::class, 'boletos'])->name('emissoes.boletos');
        Route::get('emissoes/cobranca', [\App\Http\Controllers\Financeiro\FinanceiroEmissaoController::class, 'cobranca'])->name('emissoes.cobranca');
        Route::get('emissoes/conta-corrente', [\App\Http\Controllers\Financeiro\FinanceiroEmissaoController::class, 'contaCorrente'])->name('emissoes.conta-corrente');
        Route::get('emissoes/resumo-pessoa', [\App\Http\Controllers\Financeiro\FinanceiroEmissaoController::class, 'resumoPessoa'])->name('emissoes.resumo-pessoa');
        Route::get('emissoes/fechamento-caixa', [\App\Http\Controllers\Financeiro\FinanceiroEmissaoController::class, 'fechamentoCaixa'])->name('emissoes.fechamento-caixa');
        Route::get('emissoes/comissoes', [\App\Http\Controllers\Financeiro\FinanceiroEmissaoController::class, 'comissoes'])->name('emissoes.comissoes');
        Route::get('emissoes/titulos-receber', [\App\Http\Controllers\Financeiro\FinanceiroEmissaoController::class, 'titulosReceber'])->name('emissoes.titulos-receber');
        Route::get('emissoes/lancamentos', [\App\Http\Controllers\Financeiro\FinanceiroEmissaoController::class, 'lancamentos'])->name('emissoes.lancamentos');
        Route::get('emissoes/plano-contas', [\App\Http\Controllers\Financeiro\FinanceiroEmissaoController::class, 'planoContas'])->name('emissoes.plano-contas');
        Route::get('emissoes/declaracao-pagamentos', [\App\Http\Controllers\Financeiro\FinanceiroEmissaoController::class, 'declaracaoPagamentos'])->name('emissoes.declaracao-pagamentos');
        Route::get('emissoes/pagamentos-contas-pagar', [\App\Http\Controllers\Financeiro\FinanceiroEmissaoController::class, 'pagamentosContasPagar'])->name('emissoes.pagamentos-contas-pagar');
        Route::get('emissoes/renegociacoes', [\App\Http\Controllers\Financeiro\FinanceiroEmissaoController::class, 'renegociacoes'])->name('emissoes.renegociacoes');
        Route::get('emissoes/resumo-cartao', [\App\Http\Controllers\Financeiro\FinanceiroEmissaoController::class, 'resumoCartao'])->name('emissoes.resumo-cartao');

        // Comissões editáveis por matrícula (222), Recebimento Coletivo (259), Índice (175), Link de Pagamento (230)
        Route::get('comissoes', [\App\Http\Controllers\Financeiro\ComissaoController::class, 'index'])->name('comissoes.index');
        Route::post('comissoes', [\App\Http\Controllers\Financeiro\ComissaoController::class, 'salvar'])->name('comissoes.salvar');
        Route::get('recebimento-coletivo', [\App\Http\Controllers\Financeiro\RecebimentoColetivoController::class, 'index'])->name('recebimento-coletivo.index');
        Route::post('recebimento-coletivo', [\App\Http\Controllers\Financeiro\RecebimentoColetivoController::class, 'processar'])->name('recebimento-coletivo.processar');
        Route::get('atualizacao-indice', [\App\Http\Controllers\Financeiro\AtualizacaoIndiceController::class, 'index'])->name('atualizacao-indice.index');
        Route::post('atualizacao-indice', [\App\Http\Controllers\Financeiro\AtualizacaoIndiceController::class, 'aplicar'])->name('atualizacao-indice.aplicar');
        Route::get('link-pagamento', [\App\Http\Controllers\Financeiro\LinkPagamentoController::class, 'index'])->name('link-pagamento.index');
        Route::post('link-pagamento', [\App\Http\Controllers\Financeiro\LinkPagamentoController::class, 'gerar'])->name('link-pagamento.gerar');

        // Financeiro avançado (P4)
        Route::resource('lancamentos', \App\Http\Controllers\Financeiro\LancamentoFinanceiroController::class)->parameters(['lancamentos' => 'lancamento'])->except('show');
        Route::get('caixas', [\App\Http\Controllers\Financeiro\CaixaController::class, 'index'])->name('caixas.index');
        Route::post('caixas/abrir', [\App\Http\Controllers\Financeiro\CaixaController::class, 'abrir'])->name('caixas.abrir');
        Route::get('caixas/{caixa}', [\App\Http\Controllers\Financeiro\CaixaController::class, 'show'])->name('caixas.show');
        Route::post('caixas/{caixa}/movimentar', [\App\Http\Controllers\Financeiro\CaixaController::class, 'movimentar'])->name('caixas.movimentar');
        Route::post('caixas/{caixa}/fechar', [\App\Http\Controllers\Financeiro\CaixaController::class, 'fechar'])->name('caixas.fechar');
        Route::get('dre', [\App\Http\Controllers\Financeiro\DreController::class, 'index'])->name('dre.index');
        Route::get('renegociacoes', [\App\Http\Controllers\Financeiro\RenegociacaoController::class, 'index'])->name('renegociacoes.index');
        Route::get('renegociacoes/nova', [\App\Http\Controllers\Financeiro\RenegociacaoController::class, 'create'])->name('renegociacoes.create');
        Route::post('renegociacoes', [\App\Http\Controllers\Financeiro\RenegociacaoController::class, 'store'])->name('renegociacoes.store');
        Route::get('retorno', [\App\Http\Controllers\Financeiro\RetornoCnabController::class, 'index'])->name('retorno.index');
        Route::post('retorno', [\App\Http\Controllers\Financeiro\RetornoCnabController::class, 'processar'])->name('retorno.processar');

        // Cheques (72)
        Route::resource('cheques', \App\Http\Controllers\Financeiro\ChequeController::class)->parameters(['cheques' => 'cheque'])->except('show');
        Route::post('cheques/{cheque}/situacao', [\App\Http\Controllers\Financeiro\ChequeController::class, 'situacao'])->name('cheques.situacao');

        // Cartões (70/71/136)
        Route::resource('contratos-cartao', \App\Http\Controllers\Financeiro\ContratoCartaoController::class)->parameters(['contratos-cartao' => 'contratos_cartao'])->except('show');
        Route::resource('cartoes-empresariais', \App\Http\Controllers\Financeiro\CartaoEmpresarialController::class)->parameters(['cartoes-empresariais' => 'cartoes_empresariai'])->except('show');
        Route::get('conciliacao-cartao', [\App\Http\Controllers\Financeiro\ConciliacaoCartaoController::class, 'index'])->name('conciliacao-cartao.index');
        Route::get('conciliacao-cartao/novo', [\App\Http\Controllers\Financeiro\ConciliacaoCartaoController::class, 'create'])->name('conciliacao-cartao.create');
        Route::post('conciliacao-cartao', [\App\Http\Controllers\Financeiro\ConciliacaoCartaoController::class, 'store'])->name('conciliacao-cartao.store');
        Route::post('conciliacao-cartao/{recebimento}/conciliar', [\App\Http\Controllers\Financeiro\ConciliacaoCartaoController::class, 'conciliar'])->name('conciliacao-cartao.conciliar');
        Route::delete('conciliacao-cartao/{recebimento}', [\App\Http\Controllers\Financeiro\ConciliacaoCartaoController::class, 'destroy'])->name('conciliacao-cartao.destroy');
    });

    // Comunicacao
    Route::prefix('comunicacao')->name('comunicacao.')->group(function () {
        Route::resource('templates', TemplateMensagemController::class);
        Route::get('configuracao', [\App\Http\Controllers\Comunicacao\ConfiguracaoComunicacaoController::class, 'index'])->name('configuracao.index');
        Route::put('configuracao', [\App\Http\Controllers\Comunicacao\ConfiguracaoComunicacaoController::class, 'update']);

        // Disparo de mensagens (P5)
        Route::get('mensagens', [\App\Http\Controllers\Comunicacao\MensagemController::class, 'index'])->name('mensagens.index');
        Route::get('mensagens/avulsa', [\App\Http\Controllers\Comunicacao\MensagemController::class, 'avulsaCreate'])->name('mensagens.avulsa');
        Route::post('mensagens/avulsa', [\App\Http\Controllers\Comunicacao\MensagemController::class, 'avulsaStore'])->name('mensagens.avulsa.store');
        Route::get('mensagens/avisos', [\App\Http\Controllers\Comunicacao\MensagemController::class, 'avisos'])->name('mensagens.avisos');
        Route::post('mensagens/avisos/{titulo}', [\App\Http\Controllers\Comunicacao\MensagemController::class, 'enviarAviso'])->name('mensagens.enviar-aviso');

        // Lote Comunicacao (89/234/62/260)
        Route::get('saldo-sms', [\App\Http\Controllers\Comunicacao\MensagemController::class, 'saldoSms'])->name('saldo-sms');
        Route::get('mensagens/aviso-pagamento', [\App\Http\Controllers\Comunicacao\MensagemController::class, 'avisoPagamento'])->name('mensagens.aviso-pagamento');
        Route::post('mensagens/aviso-pagamento/{titulo}', [\App\Http\Controllers\Comunicacao\MensagemController::class, 'enviarAvisoPagamento'])->name('mensagens.enviar-aviso-pagamento');
        Route::get('mensagens/interessados', [\App\Http\Controllers\Comunicacao\MensagemController::class, 'interessados'])->name('mensagens.interessados');
        Route::post('mensagens/interessados', [\App\Http\Controllers\Comunicacao\MensagemController::class, 'enviarInteressado'])->name('mensagens.enviar-interessado');
        Route::get('notificacoes', [\App\Http\Controllers\Comunicacao\NotificacaoAlunoController::class, 'index'])->name('notificacoes.index');
        Route::get('notificacoes/nova', [\App\Http\Controllers\Comunicacao\NotificacaoAlunoController::class, 'create'])->name('notificacoes.create');
        Route::post('notificacoes', [\App\Http\Controllers\Comunicacao\NotificacaoAlunoController::class, 'store'])->name('notificacoes.store');
        Route::post('notificacoes/{notificaco}/lida', [\App\Http\Controllers\Comunicacao\NotificacaoAlunoController::class, 'marcarLida'])->name('notificacoes.lida');
        Route::delete('notificacoes/{notificaco}', [\App\Http\Controllers\Comunicacao\NotificacaoAlunoController::class, 'destroy'])->name('notificacoes.destroy');
    });

    // Biblioteca
    Route::prefix('biblioteca')->name('biblioteca.')->group(function () {
        Route::resource('obras', \App\Http\Controllers\Biblioteca\ObraController::class)->except('show');
        Route::resource('exemplares', \App\Http\Controllers\Biblioteca\ExemplarController::class)->parameters(['exemplares' => 'exemplare'])->except('show');

        // Movimentacoes (287) e Reservas (289)
        Route::put('movimentacoes/{movimentacao}/devolver', [\App\Http\Controllers\Biblioteca\MovimentacaoExemplarController::class, 'devolver'])->name('movimentacoes.devolver');
        Route::put('movimentacoes/{movimentacao}/renovar', [\App\Http\Controllers\Biblioteca\MovimentacaoExemplarController::class, 'renovar'])->name('movimentacoes.renovar');
        Route::resource('movimentacoes', \App\Http\Controllers\Biblioteca\MovimentacaoExemplarController::class)->parameters(['movimentacoes' => 'movimentacao'])->only(['index', 'create', 'store', 'destroy']);
        Route::put('reservas/{reserva}/situacao', [\App\Http\Controllers\Biblioteca\ReservaExemplarController::class, 'situacao'])->name('reservas.situacao');
        Route::resource('reservas', \App\Http\Controllers\Biblioteca\ReservaExemplarController::class)->parameters(['reservas' => 'reserva'])->only(['index', 'create', 'store', 'destroy']);

        // Emissoes (283/284/285)
        Route::get('emissoes/etiquetas', [\App\Http\Controllers\Biblioteca\BibliotecaEmissaoController::class, 'etiquetas'])->name('emissoes.etiquetas');
        Route::get('emissoes/exemplares', [\App\Http\Controllers\Biblioteca\BibliotecaEmissaoController::class, 'exemplares'])->name('emissoes.exemplares');
        Route::get('emissoes/movimentacoes', [\App\Http\Controllers\Biblioteca\BibliotecaEmissaoController::class, 'movimentacoes'])->name('emissoes.movimentacoes');

        Route::get('configuracao', [\App\Http\Controllers\Biblioteca\ConfiguracaoBibliotecaController::class, 'index'])->name('configuracao.index');
        Route::put('configuracao', [\App\Http\Controllers\Biblioteca\ConfiguracaoBibliotecaController::class, 'update'])->name('configuracao.update');
    });

    // Estoque
    Route::prefix('estoque')->name('estoque.')->group(function () {
        Route::resource('produtos', ProdutoEstoqueController::class);
        Route::resource('categorias', CategoriaEstoqueController::class);
        Route::resource('unidades', UnidadeMedidaController::class);
        Route::resource('movimentacoes', MovimentacaoEstoqueController::class);
        Route::get('consulta', [\App\Http\Controllers\Estoque\ConsultaEstoqueController::class, 'index'])->name('consulta.index');
        Route::get('emissao', [\App\Http\Controllers\Estoque\ConsultaEstoqueController::class, 'emissao'])->name('emissao');
    });

    // EAD
    Route::prefix('ead')->name('ead.')->group(function () {
        Route::resource('cursos', CursoEadController::class);
        Route::resource('matriculas', \App\Http\Controllers\Ead\MatriculaEadController::class)->parameters(['matriculas' => 'matricula'])->except('show');
        Route::resource('avaliacoes', \App\Http\Controllers\Ead\AvaliacaoEadController::class)->parameters(['avaliacoes' => 'avaliacao'])->except('show');
        Route::resource('videos', \App\Http\Controllers\Ead\VideoEadController::class)->parameters(['videos' => 'video'])->except('show');
        Route::resource('questoes', \App\Http\Controllers\Ead\QuestaoAvulsaController::class)->parameters(['questoes' => 'questao'])->except('show');
        Route::resource('sub-agrupadores', \App\Http\Controllers\Ead\SubAgrupadorCursoController::class)->parameters(['sub-agrupadores' => 'sub_agrupadore'])->except('show');
        Route::resource('geradores', \App\Http\Controllers\Ead\GeradorAvaliacaoController::class)->parameters(['geradores' => 'geradore'])->except('show');
        Route::resource('foruns', \App\Http\Controllers\Ead\ForumEadController::class)->parameters(['foruns' => 'forum']);
        Route::post('foruns/{forum}/mensagem', [\App\Http\Controllers\Ead\ForumEadController::class, 'mensagem'])->name('foruns.mensagem');
        Route::get('emissoes', [\App\Http\Controllers\Ead\EadEmissaoController::class, 'index'])->name('emissoes.index');
        Route::get('emissoes/alunos-matriculados', [\App\Http\Controllers\Ead\EadEmissaoController::class, 'alunosMatriculados'])->name('emissoes.alunos-matriculados');
        Route::get('emissoes/notas-alunos', [\App\Http\Controllers\Ead\EadEmissaoController::class, 'notasAlunos'])->name('emissoes.notas-alunos');
    });

    // Placeholder routes for modules
    Route::prefix('ged')->name('ged.')->group(function () {
        Route::get('/', [GedController::class, 'index'])->name('index');
        Route::resource('classificacoes', \App\Http\Controllers\Ged\ClassificacaoGedController::class)->parameters(['classificacoes' => 'classificaco'])->except('show');
        Route::resource('documentos', \App\Http\Controllers\Ged\DocumentoGedController::class)->parameters(['documentos' => 'documento'])->except('show');
        Route::resource('atos', \App\Http\Controllers\Ged\AtoRegulatorioController::class)->parameters(['atos' => 'ato'])->except('show');
        Route::resource('diplomas', \App\Http\Controllers\Ged\DiplomaDigitalController::class)->parameters(['diplomas' => 'diploma'])->except('show');
    });
    Route::prefix('geral')->name('geral.')->group(function () {
        Route::get('/', [GeralController::class, 'index'])->name('index');
        Route::resource('questoes', \App\Http\Controllers\Geral\QuestaoController::class)->parameters(['questoes' => 'questao'])->except('show');
        Route::resource('questionarios', \App\Http\Controllers\Geral\QuestionarioController::class)->parameters(['questionarios' => 'questionario'])->except('show');
        Route::get('questionarios/{questionario}/responder', [\App\Http\Controllers\Geral\RespostaQuestionarioController::class, 'responder'])->name('questionarios.responder');
        Route::post('questionarios/{questionario}/responder', [\App\Http\Controllers\Geral\RespostaQuestionarioController::class, 'salvar'])->name('questionarios.salvar-resposta');
        Route::get('questionarios/{questionario}/resultados', [\App\Http\Controllers\Geral\RespostaQuestionarioController::class, 'resultados'])->name('questionarios.resultados');

        // Geral lote (9/164/225/223)
        Route::resource('modelos-documento', \App\Http\Controllers\Geral\ModeloDocumentoController::class)->parameters(['modelos-documento' => 'modelo'])->except('show');
        Route::get('aniversariantes', [\App\Http\Controllers\Geral\AniversariantesController::class, 'index'])->name('aniversariantes.index');
        Route::resource('campanhas-indicacao', \App\Http\Controllers\Geral\CampanhaIndicacaoController::class)->parameters(['campanhas-indicacao' => 'campanha'])->except('show');
        Route::resource('indicacoes', \App\Http\Controllers\Geral\IndicacaoController::class)->parameters(['indicacoes' => 'indicaco'])->except('show');
        Route::post('indicacoes/{indicaco}/status', [\App\Http\Controllers\Geral\IndicacaoController::class, 'status'])->name('indicacoes.status');

        // Emissões do Geral (254/131/181/235/263)
        Route::get('emissoes/pessoas', [\App\Http\Controllers\Geral\GeralEmissaoController::class, 'pessoas'])->name('emissoes.pessoas');
        Route::get('emissoes/profissionais', [\App\Http\Controllers\Geral\GeralEmissaoController::class, 'profissionais'])->name('emissoes.profissionais');
        Route::get('emissoes/professores', [\App\Http\Controllers\Geral\GeralEmissaoController::class, 'professores'])->name('emissoes.professores');
        Route::get('emissoes/atendimentos', [\App\Http\Controllers\Geral\GeralEmissaoController::class, 'atendimentos'])->name('emissoes.atendimentos');
        Route::get('emissoes/atividades-crm', [\App\Http\Controllers\Geral\GeralEmissaoController::class, 'atividadesCrm'])->name('emissoes.atividades-crm');

        // Consulta Personalizada (221)
        Route::resource('consultas', \App\Http\Controllers\Geral\ConsultaPersonalizadaController::class)->parameters(['consultas' => 'consulta'])->except('show');
        Route::get('consultas/{consulta}/executar', [\App\Http\Controllers\Geral\ConsultaPersonalizadaController::class, 'executar'])->name('consultas.executar');
        Route::get('consultas/{consulta}/csv', [\App\Http\Controllers\Geral\ConsultaPersonalizadaController::class, 'exportar'])->name('consultas.csv');
    });
    Route::prefix('integracoes')->name('integracoes.')->group(function () {
        Route::get('/', [IntegracoesController::class, 'index'])->name('index');
        Route::get('/{chave}/editar', [IntegracoesController::class, 'edit'])->name('edit');
        Route::put('/{chave}', [IntegracoesController::class, 'update'])->name('update');
        Route::post('/{chave}/testar', [IntegracoesController::class, 'testar'])->name('testar');
    });
    Route::prefix('matricula-online')->name('matricula-online.')->group(function () {
        Route::get('/', [MatriculaOnlineController::class, 'index'])->name('index');
        Route::resource('aberturas', AberturaController::class)->parameters(['aberturas' => 'abertura'])->except('show');
        Route::resource('cupons', CupomController::class)->parameters(['cupons' => 'cupom'])->except('show');
        Route::get('inscricoes', [InscricaoController::class, 'index'])->name('inscricoes.index');
        Route::get('inscricoes/create', [InscricaoController::class, 'create'])->name('inscricoes.create');
        Route::post('inscricoes', [InscricaoController::class, 'store'])->name('inscricoes.store');
        Route::put('inscricoes/{inscricao}', [InscricaoController::class, 'update'])->name('inscricoes.update');
        Route::delete('inscricoes/{inscricao}', [InscricaoController::class, 'destroy'])->name('inscricoes.destroy');

        // Lote MO (193/151/187)
        Route::resource('cupons-personalizados', \App\Http\Controllers\MatriculaOnline\CupomPersonalizadoController::class)->parameters(['cupons-personalizados' => 'cupons_personalizado'])->except('show');
        Route::get('painel', [\App\Http\Controllers\MatriculaOnline\PainelInscricoesController::class, 'index'])->name('painel.index');
        Route::get('emissao-inscricoes', [\App\Http\Controllers\MatriculaOnline\PainelInscricoesController::class, 'emissao'])->name('emissao-inscricoes');
    });
    Route::prefix('portais')->name('portais.')->group(function () {
        Route::get('/', [PortaisController::class, 'index'])->name('index');
        Route::get('/configuracao', [PortaisController::class, 'configuracao'])->name('configuracao');
        Route::put('/configuracao', [PortaisController::class, 'salvarConfiguracao'])->name('configuracao.salvar');
        Route::resource('pastas', \App\Http\Controllers\Portais\PastaPortalController::class)->parameters(['pastas' => 'pasta'])->except('show');
        Route::resource('publicacoes', \App\Http\Controllers\Portais\PublicacaoPortalController::class)->parameters(['publicacoes' => 'publicaco'])->except('show');

        // Configuração do Portal de Inscrição (92)
        Route::get('config-inscricao', [\App\Http\Controllers\Portais\ConfigInscricaoController::class, 'index'])->name('config-inscricao.index');
        Route::put('config-inscricao', [\App\Http\Controllers\Portais\ConfigInscricaoController::class, 'update'])->name('config-inscricao.update');
    });
});
