# Inventário REAL do EDUQ — capturado de dentro do sistema

> Coletado em 2026-06-29 via login automatizado (Playwright) na conta `apresentacao/brasedu`,
> navegando módulo a módulo e expandindo todas as categorias do menu.
> Esta é a **estrutura verdadeira** do EDUQ (substitui a suposição do `ANALISE_SISTEMA.md`).
> Legenda: ✅ temos · 🟡 parcial · ❌ falta

## ⚠️ Descobertas que mudam o plano
1. **Existe um módulo BIBLIOTECA inteiro** (20 funções) que não estava na análise antiga e **não existe no nosso sistema**.
2. **A organização dos módulos é diferente da nossa:**
   - **Administrativo** no EDUQ só tem "Acessos" (Operador 44, Grupo 43, Painel do Cliente 112). Tudo que é Pessoa/Profissional/Aluno/Departamento/Instituição **está em GERAL**.
   - **Cadastro de Aluno (17)** e **Forma de Ingresso (21)** ficam em **Acadêmico › Matrícula**.
   - **Diploma Digital (215), Histórico Escolar Digital (226), Atos Regulatórios (216)** ficam em **Acadêmico** (não em GED).
   - **GED** no EDUQ é genérico: Categoria do Documento (245), Documento (244), Tipo de Documento (252).
   - **NPS, Atendimentos, Indicação, Assinaturas, Modelos de Documento** ficam em **Geral**.
3. Há **dezenas de funções novas** que não estavam mapeadas (marcadas ❌ abaixo).

---

## ACADÊMICO (72 funções)
### Cadastros Essenciais
- ✅ 35 Cadastro de Calendário · ✅ 8 Cadastro de Escola · ✅ 36 Cadastro de Grade de Horário · ✅ 39 Cadastro de Sala · ✅ 42 Cadastro de Turnos · ✅ 167 Configuração do Acadêmico · ✅ 3 Configuração do Boletim · ✅ 5 Tabela de Avaliação · ✅ 268 Registro de Frequência
- ❌ 4 Programações de Avaliações
### Diploma Digital
- 🟡 215 Cadastro de Diploma Digital (temos em GED) · 🟡 226 Histórico Escolar Digital (PDF sem assinatura)
### Documentos
- ✅ 18 Cadastro de Documento · ❌ 102 Consulta Documentos não Entregues · ❌ 210 Emissão de Documentos · ❌ 19 Entrega de Documentos
### Matrícula
- ✅ 17 Cadastro de Aluno · ❌ 169 Cadastro de Tag de Matrícula · ❌ 239 Controle de Horas Complementares · ❌ 90 Controle de Prática Supervisionada · ❌ 279 Controle de Rematrículas · ❌ 79 Emissão de Alunos Matriculados · 🟡 20 Emissão de Contratos e Declarações · ❌ 305 Emissão de Disciplinas dos Alunos · ✅ 98 Emissão do Histórico Escolar · ✅ 21 Forma de Ingresso · ❌ 183 Manutenção de Exame de Nível · ✅ 23 Matrícula e Histórico · ❌ 242 Motivo de Cancelamento Matrícula
### Matriz Curricular
- ✅ 24 Cadastro de Área · 🟡 216 Cadastro de Atos Regulatórios (em GED) · ❌ 176 Cadastro de Conceito de Notas · ✅ 25 Cadastro de Curso · ✅ 26 Cadastro de Disciplina · ✅ 28 Cadastro de Grau · ✅ 29 Cadastro de Habilitação · ✅ 30 Cadastro de Matriz Curricular · ✅ 31 Cadastro de Módulos · ❌ 27 Emissão da Matriz Curricular
### Notas e Faltas
- ✅ 2 Cálculo do Boletim · ❌ 60 Emissão de Notas e Faltas · ❌ 249 Emissão de Pendências Notas e Faltas · ❌ 91 Emissão do Diário de Classe · ❌ 137 Exclusão de Notas e Faltas · ✅ 16 Frequência e Conteúdo Ministrado · ✅ 1 Lançamento de Avaliação · ❌ 262 Liberar Lançamento de Frequência
### Plano de Ensino/Aula
- ❌ 204 Cadastro de Estrutura do Plano · ❌ 203 Cadastro de Tópico do Plano · ❌ 119 Preenchimento Plano de Ensino
### Requerimentos
- ✅ 94 Cadastro de Tipo de Requerimento · ❌ 197 Emissão de Requerimentos · ✅ 96 Manutenção de Requerimentos · 🟡 95 Motivos Cancelamento (Requerimento)
### Turmas
- ✅ 38 Cadastro de Período Letivo · ❌ 251 Cadastro de Tag (Turma Montada) · ✅ 40 Cadastro de Turma · ❌ 114 Declaração de Aula Ministrada · ❌ 185 Emissão de Horários Professores · ❌ 184 Emissão de Turmas Montadas · ✅ 41 Montagem de Turma e Horário · ❌ 257 Painel do Professor · ❌ 45 Planejamento Diário de Aulas

## ADMINISTRATIVO (só Acessos)
- ✅ 43 Cadastro de Grupo de Operadores · ✅ 44 Cadastro de Operador · ✅ 112 Painel do Cliente

## BIBLIOTECA (20 funções) — ❌ MÓDULO INTEIRO FALTANDO
### Acervo
- ❌ 286 Cadastro de Exemplares · ❌ 288 Cadastro de Obra · ❌ 283 Emissão de Etiquetas · ❌ 284 Emissão de Exemplares · ❌ 285 Emissão de Movimentações · ❌ 287 Movimentações de Exemplares · ❌ 289 Reserva de Exemplares
### Cadastros Essenciais
- ❌ 290 Cadastro de Autores · ❌ 291 Cadastro de Biblioteca · ❌ 292 Cadastro de Coleção · ❌ 293 Cadastro de Editores · ❌ 294 Cadastro de Estado de Conservação · ❌ 295 Cadastro de Idiomas · ❌ 297 Cadastro de Tipo de Aquisição · ❌ 298 Cadastro de Tipo de Material · ❌ 296 Motivo de Indisponibilidade
### Configuração
- ❌ 299 Configuração do Biblioteca

## COMUNICAÇÃO (12)
### Configuração
- ❌ 260 Central de Notificação do Aluno · ✅ 85 Configuração da Comunicação · ❌ 89 Consulta de Saldo SMS · ❌ 247 Números Whatsapp · ✅ 87 Templates de Mensagens
### Mensagens
- ✅ 84 Mensagens Avulsas · ✅ 88 Aviso de Cobrança · ❌ 234 Aviso de Pagamento · ✅ 86 Aviso de Vencimento · ❌ 62 Mensagens para Interessados CRM

## ESTOQUE (9)
- ✅ 147 Categorias · ✅ 153 Depósitos · ✅ 148 Produtos · ✅ 146 Unidades de Medida · ❌ 186 Emissão de Produtos · ❌ 154 Consulta de Estoque · ✅ 150 Movimentações

## CRM (22)
### Cadastros Essenciais
- ❌ 256 Ação Automática (CRM) · ✅ 207 Categorias (Interessados) · ✅ 104 Eventos CRM · ✅ 200 Funil de Oportunidades · ✅ 191 Metas CRM · ✅ 212 Motivo de Ganho · ✅ 202 Motivo de Pausa · ✅ 107 Motivos de Perda · ✅ 103 Origem do Interessado · ✅ 206 Produtos/Serviços CRM · ✅ 171 Tag CRM · ✅ 166 Configuração do CRM · ❌ 264 Motivo de Finalização de Atividade (CRM)
### Oportunidades
- ✅ 108 Cadastro de Interessados · ✅ 190 Desempenho Individual do Consultor · ❌ 263 Emissão de Atividades (CRM) · ❌ 201 Emissão de Propostas (CRM) · ❌ 159 Exportação de Oportunidades · ✅ 110 Funil de Oportunidades · ✅ 109 Manutenção de Oportunidades

## EAD (14)
- ❌ 211 Agrupador de Cursos · ❌ 214 Avaliações EAD · ✅ 152 Cadastro de Curso (EAD) · ❌ 238 Questões Avulsas · ❌ 266 Sub Agrupador (EAD) · ❌ 246 Tag (Curso EAD) · ❌ 236 Tag de Questões · ❌ 301 Cadastro de Vídeos · ❌ 174 Emissão de Alunos Matriculados EAD · ❌ 219 Emissão de notas alunos (EAD) · ❌ 306 Fóruns EAD · ❌ 241 Gerador de Avaliações · ❌ 156 Manutenção de Matrículas EAD

## FINANCEIRO (56)
### Cadastros Essenciais
- ❌ 274 Centro de Custos · ❌ 47 Cadastro de Banco · ✅ 51 Categorias (A Pagar) · ✅ 65 Categorias (A Receber) · ❌ 227 Configurações de NFS-e · ✅ 63 Contas · ✅ 58 Desconto Condicional · ✅ 57 Desconto Incondicional · ❌ 53 Forma de Pagamento · ❌ 213 Taxas de Cartão Avulso · ✅ 50 Plano de Contas · ✅ 59 Configuração do Financeiro · ❌ 162 Emissão do Plano de Contas · ❌ 243 Grupo de Categorias (A Pagar) · ❌ 261 Motivo de Restrição
### Caixa (Movimentações)
- 🟡 106 Emissão do Fechamento de Caixa · ✅ 68 Movimentações de Caixas
### Cartões
- ❌ 70 Contratos de Cartões · ❌ 136 Cartão de Crédito Empresarial · ❌ 71 Conciliação de Recebimentos (Cartão) · ❌ 255 Resumo de Recebimentos (Cartão) · ❌ 163 Transações com Cartão (Automático)
### Cheques
- ❌ 72 Manutenção de Cheques · ❌ 73 Motivo de Devolução (Cheque)
### Lançamentos Financeiros
- ✅ 111 DRE · ❌ 161 Emissão de Lançamentos Financeiros · 🟡 195 Fluxo de Caixa (Diário) · ✅ 78 Fluxo de Caixa (Mensal) · ✅ 61 Lançamentos Financeiros
### Títulos a pagar
- ❌ 222 Cálculo de Comissões · ❌ 302 Cálculos de Hora-aula · ❌ 180 Emissão de Comissões · ❌ 258 Emissão de pagamentos Contas a Pagar · ❌ 173 Emissão de Títulos a Pagar · ✅ 52 Manutenção de Títulos a Pagar
### Títulos a receber
- ❌ 175 Atualização de Parcelas pelo Índice · ❌ 217 Agrupador de Títulos · ❌ 93 Conta Corrente Por Pessoa · 🟡 66 Emissão de Boletos Bancários · ❌ 113 Emissão de Cobrança · ❌ 192 Emissão de Conta Corrente Pessoa · ❌ 99 Emissão de Declaração de Pagamentos · ❌ 275 Emissão de Renegociação de Parcelas · ❌ 116 Emissão de Títulos a Receber · ❌ 230 Link de Pagamento Avulso · ✅ 64 Manutenção de Títulos a Receber · ❌ 259 Recebimento Coletivo (Bancário) · ✅ 80 Renegociações de Parcelas · ❌ 101 Resumo Financeiro da Pessoa

## GED (3) — estrutura real (genérico)
- ❌ 245 Categoria do Documento (GED) · ❌ 244 Documento (GED) · ❌ 252 Tipo de Documento
> Obs.: nosso "GED" atual implementa Diploma/Atos/Classificação, que no EDUQ ficam em Acadêmico.

## GERAL (40)
### Atendimentos
- ✅ 172 Atendimentos Pool (Follow up) · ✅ 54 Categorias (Atendimento) · 🟡 178 Motivos de Falha (Atendimentos) · ❌ 235 Emissão de Atendimentos · ✅ 55 Manutenção de Atendimentos
### Cadastros Essenciais
- ❌ 97 Atributos Adicionais · ✅ 67 Departamento · ✅ 7 Instituição de Ensino · 🟡 118 Questionários NPS · ❌ 276 Consulta de CPF Por Base · ❌ 221 Consulta Personalizada · ❌ 224 Emissão de Consulta Personalizada
### Configurações de Emissões
- ❌ 6 Assinatura · ❌ 9 Modelo de Documentos · ❌ 49 Modelo de Papel · ❌ 48 Modelos de Cabeçalho
### Indicação
- ❌ 225 Campanha de Indicação · ❌ 223 Controle de Indicações
### Pessoas
- ❌ 164 Aniversariantes · ✅ 198 Alergia · ✅ 10 Necessidades Especiais · ✅ 11 Cadastro de Pessoa · ✅ 12 Cadastro de Profissional · ✅ 145 Profissões · ✅ 13 Religião · ✅ 14 Tipo de Profissional · ✅ 15 Titularidade · ❌ 123 Emissão de Contratos Avulsos · ❌ 254 Emissão de Pessoas · ❌ 181 Emissão de Professores · ❌ 131 Emissão de Profissionais

## MATRÍCULA ONLINE (8)
- ✅ 140 Abertura de Matrícula Online · ✅ 149 Acompanhamento de Inscrições · ✅ 182 Cupons de Desconto · ❌ 193 Cupons Personalizados · ❌ 74 Tag Matrícula Online · ❌ 187 Emissão de Inscrições · ❌ 151 Painel de Inscrições Online

## PORTAIS (3 categorias)
- Configuração · Feedbacks · Publicação (Portal do Aluno)
- ✅ 46 Configuração Portal Aluno · ✅ 76 Pastas · ✅ 77 Publicações · ❌ Feedbacks do portal · ❌ área logada do aluno

---

## Contagem aproximada
- **EDUQ real:** ~230 funções em 13 módulos (incluindo Biblioteca).
- **Já temos:** ~90.
- **Maiores blocos faltando:** Biblioteca (20, módulo inteiro), Financeiro cartão/cheque/emissões (~25), EAD (~12), emissões/relatórios PDF (~20 espalhados), Geral emissões+indicação (~12).
