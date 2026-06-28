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
    Route::resource('pessoas', PessoaController::class);
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

    // Administrativo - controle de acesso
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('operadores', \App\Http\Controllers\Admin\OperadorController::class)->parameters(['operadores' => 'operador'])->except('show');
        Route::resource('grupos', \App\Http\Controllers\Admin\GrupoOperadorController::class)->parameters(['grupos' => 'grupo'])->except('show');
        Route::resource('departamentos', \App\Http\Controllers\Admin\DepartamentoController::class)->parameters(['departamentos' => 'departamento'])->except('show');
    });

    // Academico
    Route::prefix('academico')->name('academico.')->group(function () {
        Route::resource('cursos', CursoController::class);
        Route::resource('disciplinas', DisciplinaController::class);
        Route::resource('matrizes', MatrizCurricularController::class);
        Route::resource('turmas', TurmaController::class);
        Route::resource('matriculas', MatriculaController::class);
        Route::resource('periodos-letivos', PeriodoLetivoController::class);
        Route::resource('turnos', TurnoController::class);
        Route::resource('salas', SalaController::class);
        Route::resource('tabelas-avaliacao', TabelaAvaliacaoController::class)->except('show');
        Route::resource('configuracoes-boletim', ConfiguracaoBoletimController::class)->except('show');
        Route::get('lancamento-notas', [LancamentoNotaController::class, 'index'])->name('lancamento-notas.index');
        Route::post('lancamento-notas', [LancamentoNotaController::class, 'salvar'])->name('lancamento-notas.salvar');
        Route::get('frequencia', [FrequenciaController::class, 'index'])->name('frequencia.index');
        Route::post('frequencia', [FrequenciaController::class, 'salvar'])->name('frequencia.salvar');
        Route::get('boletim', [BoletimController::class, 'index'])->name('boletim.index');
        Route::post('boletim/consolidar', [BoletimController::class, 'consolidar'])->name('boletim.consolidar');
        Route::resource('montagem-turma', MontagemTurmaController::class)->parameters(['montagem-turma' => 'montagem_turma'])->except('show');
        Route::post('montagem-turma/{montagem_turma}/matricular', [MontagemTurmaController::class, 'matricular'])->name('montagem-turma.matricular');
        Route::delete('montagem-turma/{montagem_turma}/desmatricular/{matricula}', [MontagemTurmaController::class, 'desmatricular'])->name('montagem-turma.desmatricular');
    });

    // CRM
    Route::prefix('crm')->name('crm.')->group(function () {
        Route::resource('interessados', InteressadoController::class);
        Route::resource('funil', FunilController::class);
        Route::resource('oportunidades', OportunidadeController::class);
        Route::patch('oportunidades/{oportunidade}/mover-etapa', [OportunidadeController::class, 'moverEtapa'])->name('oportunidades.mover-etapa');
        Route::get('desempenho', [DesempenhoController::class, 'index'])->name('desempenho.index');
        Route::resource('origens', OrigemController::class)->parameters(['origens' => 'origem'])->except('show');
        Route::resource('tags', TagCrmController::class)->parameters(['tags' => 'tag'])->except('show');
        Route::resource('eventos', EventoCrmController::class)->parameters(['eventos' => 'evento'])->except('show');
        Route::resource('metas', MetaCrmController::class)->parameters(['metas' => 'meta'])->except('show');
        Route::get('configuracoes', [ConfiguracaoCrmController::class, 'index'])->name('configuracoes.index');
        Route::put('configuracoes', [ConfiguracaoCrmController::class, 'update'])->name('configuracoes.update');
    });

    // Financeiro
    Route::prefix('financeiro')->name('financeiro.')->group(function () {
        Route::get('titulos-receber/remessa', [TituloReceberController::class, 'gerarRemessa'])->name('titulos-receber.remessa');
        Route::resource('titulos-receber', TituloReceberController::class);
        Route::post('titulos-receber/{titulo}/baixar', [TituloReceberController::class, 'baixar'])->name('titulos-receber.baixar');
        Route::resource('titulos-pagar', TituloPagarController::class);
        Route::resource('plano-contas', PlanoContasController::class);
        Route::get('fluxo-caixa', [FluxoCaixaController::class, 'index'])->name('fluxo-caixa.index');
        Route::resource('categorias-receber', CategoriaReceberController::class)->except('show');
        Route::resource('categorias-pagar', CategoriaPagarController::class)->except('show');
        Route::resource('contas-bancarias', ContaBancariaController::class)->except('show');
        Route::resource('descontos', DescontoController::class)->parameters(['descontos' => 'desconto'])->except('show');
    });

    // Comunicacao
    Route::prefix('comunicacao')->name('comunicacao.')->group(function () {
        Route::resource('templates', TemplateMensagemController::class);
    });

    // Estoque
    Route::prefix('estoque')->name('estoque.')->group(function () {
        Route::resource('produtos', ProdutoEstoqueController::class);
        Route::resource('categorias', CategoriaEstoqueController::class);
        Route::resource('unidades', UnidadeMedidaController::class);
        Route::resource('movimentacoes', MovimentacaoEstoqueController::class);
    });

    // EAD
    Route::prefix('ead')->name('ead.')->group(function () {
        Route::resource('cursos', CursoEadController::class);
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
    });
    Route::prefix('portais')->name('portais.')->group(function () {
        Route::get('/', [PortaisController::class, 'index'])->name('index');
        Route::get('/configuracao', [PortaisController::class, 'configuracao'])->name('configuracao');
        Route::put('/configuracao', [PortaisController::class, 'salvarConfiguracao'])->name('configuracao.salvar');
        Route::resource('pastas', \App\Http\Controllers\Portais\PastaPortalController::class)->parameters(['pastas' => 'pasta'])->except('show');
        Route::resource('publicacoes', \App\Http\Controllers\Portais\PublicacaoPortalController::class)->parameters(['publicacoes' => 'publicaco'])->except('show');
    });
});
