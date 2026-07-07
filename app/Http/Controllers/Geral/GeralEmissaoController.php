<?php

namespace App\Http\Controllers\Geral;

use App\Http\Controllers\Controller;
use App\Models\Atendimento;
use App\Models\AtividadeOportunidade;
use App\Models\Pessoa;
use App\Models\Profissional;
use Barryvdh\DomPDF\Facade\Pdf;

/** Emissões do módulo Geral/CRM: 254 Pessoas, 131 Profissionais, 181 Professores, 235 Atendimentos, 263 Atividades (CRM). */
class GeralEmissaoController extends Controller
{
    /** 254 — Emissão de Pessoas. */
    public function pessoas()
    {
        $linhas = Pessoa::orderBy('nome')->get()->map(fn ($p) => [
            $p->nome,
            $p->cpf ?? '—',
            $p->email ?? '—',
            $p->celular ?? $p->telefone ?? '—',
            $p->cidade ?? '—',
        ]);

        return $this->pdf('Emissão de Pessoas', null, ['Nome', 'CPF', 'E-mail', 'Telefone', 'Cidade'], $linhas, 'pessoas');
    }

    /** 131 — Emissão de Profissionais. */
    public function profissionais()
    {
        $linhas = Profissional::with(['pessoa', 'tipoProfissional', 'titularidade'])->get()->map(fn ($pr) => [
            $pr->pessoa?->nome ?? '—',
            $pr->tipoProfissional?->nome ?? '—',
            $pr->titularidade?->nome ?? '—',
            $pr->ativo ? 'Ativo' : 'Inativo',
        ]);

        return $this->pdf('Emissão de Profissionais', null, ['Nome', 'Tipo', 'Titularidade', 'Situação'], $linhas, 'profissionais');
    }

    /** 181 — Emissão de Professores (profissionais do tipo professor). */
    public function professores()
    {
        $linhas = Profissional::with(['pessoa', 'tipoProfissional', 'titularidade'])
            ->get()
            ->filter(fn ($pr) => stripos((string) $pr->tipoProfissional?->nome, 'professor') !== false || stripos((string) $pr->tipoProfissional?->nome, 'docente') !== false || $pr->tipoProfissional === null)
            ->map(fn ($pr) => [
                $pr->pessoa?->nome ?? '—',
                $pr->titularidade?->nome ?? '—',
                $pr->pessoa?->email ?? '—',
                $pr->ativo ? 'Ativo' : 'Inativo',
            ]);

        return $this->pdf('Emissão de Professores', null, ['Professor', 'Titularidade', 'E-mail', 'Situação'], $linhas, 'professores');
    }

    /** 235 — Emissão de Atendimentos. */
    public function atendimentos()
    {
        $linhas = Atendimento::with(['pessoa', 'categoria', 'operador'])->orderByDesc('id')->get()->map(fn ($a) => [
            '#' . $a->id,
            $a->pessoa?->nome ?? '—',
            $a->categoria?->nome ?? '—',
            $a->operador?->nome ?? '—',
            match ($a->situacao) { 'concluido' => 'Concluído', 'em_andamento' => 'Em andamento', 'falha' => 'Falha', default => 'Aberto' },
            $a->objetivo_alcancado === null ? '—' : ($a->objetivo_alcancado ? 'Sim' : 'Não'),
        ]);

        return $this->pdf('Emissão de Atendimentos', null,
            ['Protocolo', 'Pessoa', 'Categoria', 'Operador', 'Situação', 'Objetivo alcançado'], $linhas, 'atendimentos');
    }

    /** 263 — Emissão de Atividades (CRM). */
    public function atividadesCrm()
    {
        $linhas = AtividadeOportunidade::with(['oportunidade.interessado', 'responsavel', 'eventoCrm'])->orderByDesc('id')->get()->map(fn ($e) => [
            optional($e->data_agendamento)->format('d/m/Y H:i') ?? '—',
            $e->titulo ?: ($e->eventoCrm?->nome ?? '—'),
            $e->oportunidade?->interessado?->nome ?? '—',
            $e->responsavel?->nome ?? '—',
            $e->data_conclusao ? 'Concluída' : ucfirst($e->situacao ?? 'Pendente'),
        ]);

        return $this->pdf('Emissão de Atividades (CRM)', null,
            ['Data', 'Atividade', 'Interessado', 'Responsável', 'Situação'], $linhas, 'atividades_crm');
    }

    private function pdf(string $titulo, ?string $subtitulo, array $colunas, $linhas, string $arquivo)
    {
        $linhas = collect($linhas)->map(fn ($l) => array_values((array) $l))->values()->all();
        $pdf = Pdf::loadView('emissoes.academico.relatorio', compact('titulo', 'subtitulo', 'colunas', 'linhas'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream($arquivo . '.pdf');
    }
}
