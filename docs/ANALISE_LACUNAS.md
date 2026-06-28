# Análise de Lacunas — BrasEduCRM x EDUQ

> Comparação do catálogo de funções da EDUQ (ver `ANALISE_SISTEMA.md`, 200+ funções)
> contra o que está implementado no BrasEduCRM (≈75 telas funcionais).
> Legenda: ✅ feito · 🟡 parcial · ❌ falta
>
> **Nota:** não foi possível inspecionar o app logado da EDUQ (SPA protegida). Base = catálogo de IDs do ANALISE_SISTEMA.md.

## Resumo executivo
- **Fluxos principais COMPLETOS:** CRM (funil→oportunidade), Acadêmico (montagem→matrícula→notas→frequência→boletim), Financeiro básico (títulos, plano de contas, remessa CNAB), Matrícula Online, GED, Portais, Geral (questionários/NPS), Integrações (estrutura + RD Station).
- **Maiores lacunas:** (1) Operadores/Grupos/Permissões; (2) dezenas de cadastros-base (Área, Grau, Habilitação, Módulo, Religião, Profissão, etc.); (3) Emissões/relatórios em PDF; (4) Financeiro avançado (DRE, caixa, renegociação, NF, cheques, cartão); (5) Comunicação (envio de mensagens); (6) EAD detalhado; (7) Planos de ensino/aula.

---

## 2.2 Acadêmico
✅ Curso (25), Disciplina (26), Matriz (30), Turma (40), Montagem de Turma (41), Matrícula (23), Lançamento de Avaliação (1), Cálculo do Boletim (2), Config. Boletim (3), Tabela de Avaliação (5), Frequência (16), Período Letivo (38), Sala (39), Turnos (42), Histórico Escolar (98), Painel Acadêmico (144)
🟡 Calendário (35) só visual · Horário (37) só dentro da montagem
❌ Emissão da Matriz (27), Programações de Avaliações (4), Grade de Horário (36), Planejamento Diário de Aulas (45), Módulos (31), Área (24), Grau (28), Habilitação (29), Conceito de Notas (176), Prática Supervisionada (90), Emissão Notas/Faltas (60), Exclusão Notas/Faltas (137), Diário de Classe (91), Emissão Alunos Matriculados (79), Emissão Turmas Montadas (184), Emissão Horários Professores (185), Emissão de Professores (131), Painel Resultado por Turma (170), Config. do Acadêmico (167)

## 2.3 Administrativo
✅ Pessoa (11), Aluno (17), Documento (18), Requerimentos (96), Atendimentos (55)
🟡 Emissão de Contratos/Declarações (20) → temos declaração de matrícula
❌ Profissional (12), Entrega de Documentos (19), Contratos Avulsos (123), Forma de Ingresso (21), Necessidades Especiais (10), Religião (13), Tipo de Profissional (14), Titularidade (15), Profissões (145), Alergia (198), Escola (8), Instituição de Ensino (7), Assinatura (6), Modelo de Documentos (9), Modelos de Cabeçalho (48), **Operador (44)**, **Grupo de Operadores (43)**, Departamento (67), Atributos Adicionais (97), Aniversariantes (164), Consulta Docs não Entregues (102), Aprovação de Fotos (189)

## 2.4 Comunicação
✅ Templates de Mensagens (87)
❌ Mensagens Avulsas (84), Mensagens Interessados CRM (62), Aviso de Vencimento (86), Aviso de Cobrança (88), Aviso de Pagamento (234), Config. Comunicação (85), Consulta Saldo SMS (89)
> Obs.: services de SMS/WhatsApp/e-mail existem; falta a UI de disparo.

## 2.5 Estoque
✅ Unidades (146), Categorias (147), Produtos (148), Movimentações (150)
❌ Depósitos (153), Consulta de Estoque (154), Emissão de Produtos (186)

## 2.6 CRM
✅ Interessados (108), Origem (103), Eventos (104), Tag (171), Cadastro de Funil (200), Funil de Oportunidades (110), Manutenção de Oportunidades (109), Desempenho do Consultor (190), Metas (191), Painel Comercial (142), Config. CRM (166)
🟡 Propostas CRM (201) → model existe, sem UI
❌ Motivos de Perda (107), Motivo de Ganho (212), Motivo de Pausa (202), Exportação de Oportunidades (159), Categorias Interessados (207), Produtos/Serviços CRM (206), Atendimentos Pool/Follow up (172)

## 2.7 EAD
✅ Curso EAD (152)
❌ Avaliações EAD (214), Matrículas EAD (156), Agrupador de Cursos (211), Emissão Alunos EAD (174), Painel Acadêmico EAD (188)

## 2.8 Financeiro
✅ Títulos a Receber (64), Títulos a Pagar (52), Plano de Contas (50), Categorias a Receber (65), Categorias a Pagar (51), Contas (63), Desconto Incondicional (57), Geração de Remessa CNAB (56), Fluxo de Caixa Mensal (78), Painel Financeiro (138)
🟡 Desconto Condicional (58) model existe · Emissão de Boletos (66) só remessa · Recibo de Pagamento (75) → temos recibo de título · Confirmação de Baixa Manual (160) → baixar título
❌ Config. Financeiro (59), Lançamentos Financeiros (61), Importação Retorno CNAB (82), Movimentações de Caixa (68), Fechamento de Caixa (106), Renegociações (80), Confissão de Dívida (81), Notas Fiscais (83), Cheques (72), Contratos de Cartões (70), Conciliação Cartão (71), Taxas de Cartão (213), Transações Cartão Auto (163), Inconsistências Boleto/Cartão (117/165), Atualização Parcelas por Índice (175), Conta Corrente por Pessoa (93), Cartão Crédito Empresarial (136), Agrupador de Títulos (217), Fluxo de Caixa Diário (195), **DRE (111)**, Resultado por Turma (170), e ~10 Emissões (116, 173, 161, 162, 113, 99, 180, 101, 192, 209)

## 2.9 GED
✅ Classificação GED (218), Diploma Digital (215), Atos Regulatórios (216), Documentos (upload)
🟡 Histórico Escolar Digital (226) → temos histórico em PDF, sem assinatura digital

## 2.10 Geral
✅ Questões (33), Questionário (34), Questionários NPS (118), Questionário Avulso (228 - responder)
🟡 Opções (32) dentro de questões
❌ Tag de Questões (236), Questões Avulsas (238), Feedback Professores (69), Plano de Ensino (119), Plano de Aula (205), Tópico do Plano (203), Estrutura do Plano (204), Consulta Personalizada (221/224), Cálculo de Comissões (222)

## 2.11 Integrações
✅ Painel do Cliente (112), estrutura de integrações + RD Station funcional
🟡 Histórico do RD Station (231) → integração existe, falta tela de histórico
❌ Config. da Comunicação (85)

## 2.12 Matrícula Online
✅ Abertura (140), Acompanhamento de Inscrições (149), Cupons de Desconto (182), Painel/Dashboard (151)
❌ Tag Matrícula Online (74), Tag de Matrícula (169), Cupons Personalizados (193), Emissão de Inscrições (187), Config. Portal de Inscrição (92), Link de Pagamento Avulso (230)

## 2.13 Portais
✅ Config. Portal Aluno (46), Pastas (76), Publicações (77)
> Falta: portal acessível pelo próprio aluno (área logada do aluno).

---

## Prioridade sugerida para fechar lacunas
1. **Operadores, Grupos e Permissões (43, 44)** — controle de acesso é base de qualquer CRM multi-usuário.
2. **Cadastros-base** (Área, Grau, Habilitação, Módulo, Religião, Profissão, Tipo Profissional, Titularidade, Forma de Ingresso, Depósito, Motivos CRM, Categoria Interessado, Produto/Serviço CRM) — são CRUDs rápidos (models já existem).
3. **Profissional (12)** e Config. dos módulos (167, 59, 85).
4. **Financeiro avançado**: Lançamentos (61), Movimentação/Fechamento de Caixa (68/106), Renegociação (80), DRE (111), Importação de Retorno CNAB (82).
5. **Comunicação**: telas de disparo de mensagens (84, 86, 88) usando os services já prontos.
6. **EAD** (avaliações, matrículas, painel) e **Planos de Ensino/Aula**.
7. **Emissões/Relatórios em PDF** restantes (diário de classe, alunos matriculados, etc.) — temos DomPDF instalado.
