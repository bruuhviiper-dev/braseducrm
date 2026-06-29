# Análise de Lacunas — BrasEduCRM × EDUQ (MAPA COMPLETO)

> Comparação do catálogo de funções da EDUQ (ver `ANALISE_SISTEMA.md`, 200+ funções)
> contra o que está implementado no BrasEduCRM.
> Legenda: ✅ feito · 🟡 parcial/incompleto · ❌ falta
>
> **Atualizado em:** 2026-06-29 (revisão completa módulo a módulo, pós P1–P5).
> Validação visual confirmada com prints do EDUQ no módulo Acadêmico › Cadastros Essenciais.

## Resumo executivo
- **Implementado:** ~85 telas funcionais de ~200 funções do EDUQ (~42%).
- **Núcleos completos:** Controle de acesso (operadores/grupos/permissões/departamentos), Cadastros-base (19 tabelas), CRM (funil→oportunidade), Acadêmico operacional (montagem→matrícula→notas→frequência→boletim), Financeiro (básico + avançado: lançamentos, caixa, renegociação, DRE, retorno CNAB), Comunicação (templates + disparo avulso/avisos), Estoque, GED, Portais, Matrícula Online, Geral (questionários).
- **Maiores lacunas remanescentes:** (1) Acadêmico — vários **cadastros essenciais** (Calendário, Grade de Horário, Horário, Conceito de Notas) + **emissões/relatórios**; (2) **EAD** quase inteiro; (3) **Financeiro** — cartão, cheque, NF e ~10 emissões; (4) **Geral** — Planos de Ensino/Aula; (5) emissões em PDF espalhadas por todos os módulos.

---

## 2.2 Acadêmico
### Cadastros Essenciais (validado com prints do EDUQ)
- ✅ Sala (39), Turnos (42), Período Letivo (38), Config. do Acadêmico (167), Config. do Boletim (3), Registro de Frequência (16/268), Tabela de Avaliação (5)
- ❌ **Cadastro de Calendário (35)** — ano letivo + marcação de dias letivos/não-letivos por mês, feriados com observação. **Não existe.**
- ❌ **Cadastro de Grade de Horário (36)** — Descrição + Turno + lista de Horários de Aula (início/fim, hora-aula). **Não existe.**
- ❌ **Cadastro de Horário (37)** — só existe embutido na montagem de turma.
- 🟡 **Cadastro de Escola (8)** — existe (Nome/Cidade/UF) mas **faltam campos**: Telefone fixo e **Tipo da Escola** (Privada / Pública Estadual / Municipal / Federal / Conveniada).
- ❌ **Programações de Avaliações (4)** — distinto de Tabela de Avaliação; não existe.

### Operacional / Cadastros
- ✅ Curso (25), Disciplina (26), Matriz (30), Turma (40), Montagem de Turma (41), Matrícula e Histórico (23), Lançamento de Avaliação (1), Cálculo do Boletim (2), Módulos (31), Área (24), Grau (28), Habilitação (29)
- ❌ Conceito de Notas (176), Controle de Prática Supervisionada (90), Planejamento Diário de Aulas (45)
- 🟡 Frequência (16) — falta a parte de **Conteúdo Ministrado**.

### Emissões / Painéis
- ✅ Histórico Escolar (98), Painel Acadêmico Geral (144)
- ❌ Emissão da Matriz Curricular (27), Emissão de Notas e Faltas (60), Exclusão de Notas e Faltas (137), Diário de Classe (91), Emissão de Alunos Matriculados (79), Emissão de Turmas Montadas (184), Emissão de Horários Professores (185), Emissão de Professores (131), Painel Resultado por Turma (170)

## 2.3 Administrativo
- ✅ Pessoa (11), Aluno (17), Profissional (12), Documento (18), Forma de Ingresso (21), Necessidades Especiais (10), Religião (13), Tipo de Profissional (14), Titularidade (15), Profissões (145), Alergia (198), Instituição de Ensino (7), Operador (44), Grupo de Operadores (43), Departamento (67)
- 🟡 Escola (8) — campos incompletos (ver Acadêmico) · Emissão de Contratos/Declarações (20) — só declaração de matrícula
- ❌ Entrega de Documentos (19), Contratos Avulsos (123), Assinatura (6), Modelo de Documentos (9), Modelos de Cabeçalho (48), Atributos Adicionais (97), Aniversariantes (164), Consulta Docs não Entregues (102), Aprovação de Fotos do Aluno (189)

## 2.4 Comunicação
- ✅ Templates (87), Config. Comunicação (85), Mensagens Avulsas (84), Aviso de Vencimento (86), Aviso de Cobrança (88)
- ❌ Mensagens para Interessados CRM (62), Aviso de Pagamento (234), Consulta de Saldo SMS (89)

## 2.5 Estoque
- ✅ Unidades (146), Categorias (147), Produtos (148), Depósitos (153), Movimentações (150)
- ❌ Consulta de Estoque (154), Emissão de Produtos de Estoques (186)

## 2.6 CRM
- ✅ Interessados (108), Origem (103), Eventos (104), Motivos Perda/Ganho/Pausa (107/212/202), Tag (171), Cadastro de Funil (200), Funil de Oportunidades (110), Manutenção de Oportunidades (109), Desempenho do Consultor (190), Metas (191), Categorias Interessados (207), Produtos/Serviços CRM (206), Painel Comercial (142), Config. CRM (166)
- 🟡 Atendimentos Pool/Follow up (172) — temos atendimentos, falta o "pool"
- ❌ Exportação de Oportunidades (159), Emissão de Propostas CRM (201)

## 2.7 EAD  ⚠️ módulo quase inteiro pendente
- ✅ Curso EAD (152)
- ❌ Avaliações EAD (214), Manutenção de Matrículas EAD (156), Agrupador de Cursos (211), Emissão de Alunos Matriculados EAD (174), Painel Acadêmico EAD (188)

## 2.8 Financeiro
- ✅ Títulos a Receber (64), Títulos a Pagar (52), Plano de Contas (50), Categorias Receber/Pagar (65/51), Contas (63), Desconto Incondicional/Condicional (57/58), Config. Financeiro (59), Lançamentos Financeiros (61), Geração de Remessa (56), Importação de Retorno (82), Movimentações de Caixa (68), Renegociações (80), Fluxo de Caixa Mensal (78), DRE (111), Painel Financeiro (138)
- 🟡 Emissão de Boletos (66) — só remessa CNAB · Fechamento de Caixa (106) · Recibo de Pagamento Caixa (75) — temos recibo de título · Confirmação de Baixa Manual (160) — baixar título · Fluxo de Caixa Diário (195)
- ❌ Confissão de Dívida (81), Notas Fiscais (83), Cheques (72), Contratos de Cartões (70), Conciliação Cartão (71), Taxas de Cartão (213), Transações Cartão Auto (163), Inconsistências Boleto/Cartão (117/165), Atualização de Parcelas por Índice (175), Conta Corrente por Pessoa (93), Cartão de Crédito Empresarial (136), Agrupador de Títulos (217), Resultado por Turma (170)
- ❌ Emissões: Títulos a Receber (116), Títulos a Pagar (173), Lançamentos (161), Plano de Contas (162), Cobrança (113), Declaração de Pagamentos (99), Comissões (180), Resumo Financeiro da Pessoa (101), Conta Corrente Pessoa (192), Recebimentos de Boletos Automáticos (209)

## 2.9 GED
- ✅ Classificação GED (218), Diploma Digital (215), Atos Regulatórios (216), Documentos (upload)
- 🟡 Histórico Escolar Digital (226) — PDF sem assinatura digital

## 2.10 Geral
- ✅ Questões (33), Questionário (34)
- 🟡 Opções (32) dentro de questões
- ❌ Questionários NPS (118), Questionário Avulso (228), Tag de Questões (236), Questões Avulsas (238), Feedback Professores (69), **Plano de Ensino (119)**, **Plano de Aula (205)**, Tópico do Plano (203), Estrutura do Plano (204), Consulta Personalizada (221/224), Cálculo de Comissões (222)

## 2.11 Integrações
- ✅ Painel do Cliente (112), estrutura de integrações + RD Station, Config. Comunicação (85)
- ❌ Histórico do RD Station (231) — falta tela de histórico

## 2.12 Matrícula Online
- ✅ Abertura (140), Acompanhamento de Inscrições (149), Cupons de Desconto (182)
- ❌ Painel de Inscrições Online (151), Tag Matrícula Online (74), Tag de Matrícula (169), Cupons Personalizados (193), Emissão de Inscrições (187), Config. Portal de Inscrição (92), Link de Pagamento Avulso (230)

## 2.13 Portais
- ✅ Config. Portal Aluno (46), Pastas (76), Publicações (77)
- ❌ Área logada acessível pelo próprio aluno

---

## Prioridade sugerida para fechar as lacunas restantes

### P6 — Acadêmico: Cadastros Essenciais faltantes (validados com prints)
- Calendário (35), Grade de Horário (36), Horário (37), completar Escola (8), Programações de Avaliações (4), Conceito de Notas (176)

### P7 — EAD completo
- Avaliações EAD (214), Matrículas EAD (156), Agrupador (211), Emissão (174), Painel EAD (188)

### P8 — Geral: Planos de Ensino/Aula + NPS
- Plano de Ensino (119), Plano de Aula (205), Tópico (203), Estrutura (204), NPS (118), Feedback Professores (69)

### P9 — Financeiro: cartão, cheque, NF
- Notas Fiscais (83), Cheques (72), Contratos/Conciliação de Cartão (70/71), Confissão de Dívida (81), Conta Corrente por Pessoa (93)

### P10 — Emissões/Relatórios em PDF (todos os módulos)
- Acadêmico (27, 60, 91, 79, 184, 185, 131), Financeiro (116, 173, 161, 162, 113, 99, 180, 101), Estoque (186), Matrícula Online (187)

### P11 — Administrativo: documentos e assinaturas
- Modelo de Documentos (9), Cabeçalho (48), Assinatura (6), Entrega de Documentos (19), Atributos Adicionais (97), Aniversariantes (164)

### P12 — Refinos finais
- Consulta de Estoque (154), Saldo SMS (89), Mensagens para Interessados (62), Exportação de Oportunidades (159), Histórico RD Station (231), Portal do Aluno logado
